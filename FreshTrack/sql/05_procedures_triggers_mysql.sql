-- MySQL-compatible stored procedures and triggers

DELIMITER $$

CREATE PROCEDURE add_new_batch(
    IN p_purchase_id INT,
    IN p_product_id INT,
    IN p_batch_code VARCHAR(50),
    IN p_manufacture_date DATE,
    IN p_expiry_date DATE,
    IN p_quantity_received INT,
    IN p_unit_cost DECIMAL(12,2),
    IN p_storage_location VARCHAR(100)
)
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
END$$

CREATE PROCEDURE record_waste(
    IN p_batch_id INT,
    IN p_recorded_by INT,
    IN p_quantity_wasted INT,
    IN p_reason VARCHAR(255)
)
BEGIN
    INSERT INTO waste_records (batch_id, recorded_by, quantity_wasted, reason)
    VALUES (p_batch_id, p_recorded_by, p_quantity_wasted, p_reason);

    UPDATE batches
    SET quantity_available = GREATEST(quantity_available - p_quantity_wasted, 0),
        batch_status = CASE
            WHEN GREATEST(quantity_available - p_quantity_wasted, 0) = 0 THEN 'WASTED'
            ELSE batch_status
        END
    WHERE batch_id = p_batch_id;
END$$

CREATE PROCEDURE generate_expiry_alerts(IN p_days INT)
BEGIN
    INSERT INTO alerts (batch_id, alert_type, status, message)
    SELECT b.batch_id,
           'EXPIRY',
           'OPEN',
           CONCAT('Batch ', b.batch_code, ' is expiring soon')
    FROM batches b
    WHERE b.expiry_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL p_days DAY)
      AND NOT EXISTS (
          SELECT 1
          FROM alerts a
          WHERE a.batch_id = b.batch_id
            AND a.alert_type = 'EXPIRY'
            AND a.status = 'OPEN'
      );
END$$

CREATE FUNCTION get_total_available_stock(p_product_id INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_total_stock INT DEFAULT 0;
    SELECT COALESCE(SUM(quantity_available), 0) INTO v_total_stock
    FROM batches
    WHERE product_id = p_product_id;
    RETURN v_total_stock;
END$$

CREATE PROCEDURE list_low_stock_products()
BEGIN
    SELECT p.product_name,
           c.category_name,
           p.reorder_level,
           COALESCE(SUM(b.quantity_available), 0) AS total_stock
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN batches b ON p.product_id = b.product_id
    GROUP BY p.product_name, c.category_name, p.reorder_level
    HAVING COALESCE(SUM(b.quantity_available), 0) <= p.reorder_level
    ORDER BY total_stock;
END$$

CREATE TRIGGER trg_validate_batch_dates
BEFORE INSERT ON batches
FOR EACH ROW
BEGIN
    IF NEW.manufacture_date IS NOT NULL AND NEW.expiry_date < NEW.manufacture_date THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Expiry date must be on or after manufacture date.';
    END IF;
END$$

CREATE TRIGGER trg_batches_low_stock_alert
AFTER INSERT ON batches
FOR EACH ROW
BEGIN
    DECLARE v_reorder_level INT;

    SELECT reorder_level INTO v_reorder_level
    FROM products
    WHERE product_id = NEW.product_id;

    IF NEW.quantity_available <= v_reorder_level THEN
        INSERT INTO alerts (batch_id, alert_type, status, message)
        SELECT NEW.batch_id,
               'LOW_STOCK',
               'OPEN',
               CONCAT('Batch ', NEW.batch_code, ' is at or below reorder level')
        WHERE NOT EXISTS (
            SELECT 1
            FROM alerts a
            WHERE a.batch_id = NEW.batch_id
              AND a.alert_type = 'LOW_STOCK'
              AND a.status = 'OPEN'
        );
    END IF;
END$$

CREATE TRIGGER trg_inv_movement_upd_stock
AFTER INSERT ON inventory_movements
FOR EACH ROW
BEGIN
    IF NEW.movement_type IN ('OUT', 'DONATE', 'WASTE') THEN
        UPDATE batches
        SET quantity_available = quantity_available - NEW.quantity
        WHERE batch_id = NEW.batch_id;
    ELSEIF NEW.movement_type IN ('IN', 'ADJUST') THEN
        UPDATE batches
        SET quantity_available = quantity_available + NEW.quantity
        WHERE batch_id = NEW.batch_id;
    END IF;
END$$

DELIMITER ;
