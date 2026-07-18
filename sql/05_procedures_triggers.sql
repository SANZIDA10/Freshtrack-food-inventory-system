
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

CREATE OR REPLACE TRIGGER trg_validate_batch_dates
BEFORE INSERT OR UPDATE ON batches
FOR EACH ROW
BEGIN
	IF :NEW.manufacture_date IS NOT NULL AND :NEW.expiry_date < :NEW.manufacture_date THEN
		RAISE_APPLICATION_ERROR(-20001, 'Expiry date must be on or after manufacture date.');
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

