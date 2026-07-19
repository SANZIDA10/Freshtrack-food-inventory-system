
CREATE OR REPLACE PROCEDURE add_new_batch (
	p_purchase_id IN NUMBER,
	p_product_id IN NUMBER,
	p_batch_code IN VARCHAR2,
	p_manufacture_date IN DATE,
	p_expiry_date IN DATE,
	p_quantity_received IN NUMBER,
	p_unit_cost IN NUMBER,
	p_storage_location IN VARCHAR2
) AS
BEGIN
	INSERT INTO batches (
		purchase_id,
		product_id,
		batch_code,
		manufacture_date,
		expiry_date,
		quantity_received,
		quantity_available,
		unit_cost,
		storage_location,
		batch_status
	) VALUES (
		p_purchase_id,
		p_product_id,
		p_batch_code,
		p_manufacture_date,
		p_expiry_date,
		p_quantity_received,
		p_quantity_received,
		p_unit_cost,
		p_storage_location,
		'IN_STOCK'
	);
END;
/

CREATE OR REPLACE PROCEDURE record_waste (
	p_batch_id IN NUMBER,
	p_recorded_by IN NUMBER,
	p_quantity_wasted IN NUMBER,
	p_reason IN VARCHAR2
) AS
BEGIN
	INSERT INTO waste_records (
		batch_id,
		recorded_by,
		quantity_wasted,
		reason
	) VALUES (
		p_batch_id,
		p_recorded_by,
		p_quantity_wasted,
		p_reason
	);

	UPDATE batches
	SET quantity_available = quantity_available - p_quantity_wasted,
		batch_status = CASE
			WHEN quantity_available - p_quantity_wasted = 0 THEN 'WASTED'
			ELSE batch_status
		END
	WHERE batch_id = p_batch_id;
END;
/

CREATE OR REPLACE PROCEDURE generate_expiry_alerts (
	p_days IN NUMBER DEFAULT 7
) AS
BEGIN
	INSERT INTO alerts (batch_id, alert_type, status, message)
	SELECT b.batch_id,
		   'EXPIRY',
		   'OPEN',
		   'Batch ' || b.batch_code || ' is expiring soon'
	FROM batches b
	WHERE b.expiry_date BETWEEN SYSDATE AND SYSDATE + p_days
	  AND NOT EXISTS (
		  SELECT 1
		  FROM alerts a
		  WHERE a.batch_id = b.batch_id
			AND a.alert_type = 'EXPIRY'
			AND a.status = 'OPEN'
	  );
END;
/

CREATE OR REPLACE FUNCTION get_total_available_stock (
	p_product_id IN NUMBER
) RETURN NUMBER AS
	v_total_stock NUMBER := 0;
BEGIN
	SELECT NVL(SUM(quantity_available), 0)
	INTO v_total_stock
	FROM batches
	WHERE product_id = p_product_id;

	RETURN v_total_stock;
END;
/

CREATE OR REPLACE PROCEDURE list_low_stock_products AS
	CURSOR cur_low_stock IS
		SELECT p.product_name,
		       c.category_name,
		       p.reorder_level,
		       NVL(SUM(b.quantity_available), 0) AS total_stock
		FROM products p
		JOIN categories c ON p.category_id = c.category_id
		LEFT JOIN batches b ON p.product_id = b.product_id
		GROUP BY p.product_name, c.category_name, p.reorder_level
		HAVING NVL(SUM(b.quantity_available), 0) <= p.reorder_level
		ORDER BY total_stock;

	v_product_name products.product_name%TYPE;
	v_category_name categories.category_name%TYPE;
	v_reorder_level products.reorder_level%TYPE;
	v_total_stock NUMBER;
BEGIN
	OPEN cur_low_stock;
	LOOP
		FETCH cur_low_stock INTO v_product_name, v_category_name, v_reorder_level, v_total_stock;
		EXIT WHEN cur_low_stock%NOTFOUND;

		DBMS_OUTPUT.PUT_LINE(
			v_product_name || ' (' || v_category_name || ') - stock: ' || v_total_stock ||
			', reorder level: ' || v_reorder_level
		);
	END LOOP;
	CLOSE cur_low_stock;
END;
/

CREATE OR REPLACE TRIGGER trg_validate_batch_dates
BEFORE INSERT OR UPDATE ON batches
FOR EACH ROW
BEGIN
	IF :NEW.manufacture_date IS NOT NULL AND :NEW.expiry_date < :NEW.manufacture_date THEN
		RAISE_APPLICATION_ERROR(-20001, 'Expiry date must be on or after manufacture date.');
	END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_batches_low_stock_alert
AFTER INSERT OR UPDATE OF quantity_available ON batches
FOR EACH ROW
DECLARE
	v_reorder_level products.reorder_level%TYPE;
BEGIN
	SELECT reorder_level
	INTO v_reorder_level
	FROM products
	WHERE product_id = :NEW.product_id;

	IF :NEW.quantity_available <= v_reorder_level THEN
		INSERT INTO alerts (batch_id, alert_type, status, message)
		SELECT :NEW.batch_id,
		       'LOW_STOCK',
		       'OPEN',
		       'Batch ' || :NEW.batch_code || ' is at or below reorder level'
		FROM dual
		WHERE NOT EXISTS (
			SELECT 1
			FROM alerts a
			WHERE a.batch_id = :NEW.batch_id
			  AND a.alert_type = 'LOW_STOCK'
			  AND a.status = 'OPEN'
		);
	END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_inv_movement_upd_stock
AFTER INSERT ON inventory_movements
FOR EACH ROW
BEGIN
	IF :NEW.movement_type IN ('OUT', 'DONATE', 'WASTE') THEN
		UPDATE batches
		SET quantity_available = quantity_available - :NEW.quantity
		WHERE batch_id = :NEW.batch_id;
	ELSIF :NEW.movement_type IN ('IN', 'ADJUST') THEN
		UPDATE batches
		SET quantity_available = quantity_available + :NEW.quantity
		WHERE batch_id = :NEW.batch_id;
	END IF;
END;
/

