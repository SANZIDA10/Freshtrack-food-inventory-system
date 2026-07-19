
-- 1. Basic SQL query
SELECT product_id, product_name, status
FROM products
WHERE status = 'ACTIVE'
ORDER BY product_name;

-- 2. Aggregate functions with HAVING
SELECT c.category_name,
       COUNT(p.product_id) AS product_count,
       SUM(b.quantity_available) AS total_stock
FROM categories c
LEFT JOIN products p ON c.category_id = p.category_id
LEFT JOIN batches b ON p.product_id = b.product_id
GROUP BY c.category_name
HAVING SUM(NVL(b.quantity_available, 0)) > 0
ORDER BY total_stock DESC;

-- 3. Subquery and set operation example
SELECT product_name
FROM products
WHERE product_id IN (
    SELECT product_id
    FROM batches
    WHERE quantity_available <= 5
)
UNION
SELECT product_name
FROM products
WHERE product_id IN (
    SELECT product_id
    FROM batches
    WHERE expiry_date <= SYSDATE + 3
);

-- 4. Join and outer join examples
SELECT p.product_name, b.batch_code, b.quantity_available
FROM products p
INNER JOIN batches b ON p.product_id = b.product_id;

SELECT p.product_name, b.batch_code
FROM products p
LEFT OUTER JOIN batches b ON p.product_id = b.product_id;

SELECT p.product_name, b.batch_code
FROM products p
RIGHT OUTER JOIN batches b ON p.product_id = b.product_id;

SELECT p.product_name, b.batch_code
FROM products p
FULL OUTER JOIN batches b ON p.product_id = b.product_id;

-- 5. Views and indexes
CREATE OR REPLACE VIEW v_lab_stock_summary AS
SELECT p.product_name,
       c.category_name,
       NVL(SUM(b.quantity_available), 0) AS total_available_stock
FROM products p
JOIN categories c ON p.category_id = c.category_id
LEFT JOIN batches b ON p.product_id = b.product_id
GROUP BY p.product_name, c.category_name;

CREATE INDEX idx_products_status ON products(status);
CREATE INDEX idx_batches_expiry ON batches(expiry_date);

-- 6. PL/SQL anonymous block with exception handling
DECLARE
    v_total_stock NUMBER;
BEGIN
    SELECT NVL(SUM(quantity_available), 0)
    INTO v_total_stock
    FROM batches;

    DBMS_OUTPUT.PUT_LINE('Total stock in batches: ' || v_total_stock);
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('No batch stock data found.');
END;
/

-- 7. Procedure example
CREATE OR REPLACE PROCEDURE mark_batches_low_stock AS
BEGIN
    UPDATE batches
    SET batch_status = 'LOW_STOCK'
    WHERE quantity_available <= 5;
END;
/

-- 8. Function example
CREATE OR REPLACE FUNCTION get_batch_count RETURN NUMBER AS
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count FROM batches;
    RETURN v_count;
END;
/

-- 9. Cursor example
DECLARE
    CURSOR c_expiring_batches IS
        SELECT batch_code, expiry_date
        FROM batches
        WHERE expiry_date <= SYSDATE + 3;
BEGIN
    FOR rec IN c_expiring_batches LOOP
        DBMS_OUTPUT.PUT_LINE(rec.batch_code || ' expires on ' || rec.expiry_date);
    END LOOP;
END;
/

-- 10. Trigger example
CREATE OR REPLACE TRIGGER trg_demo_alert_message
AFTER INSERT ON alerts
FOR EACH ROW
BEGIN
    IF :NEW.alert_type = 'LOW_STOCK' THEN
        DBMS_OUTPUT.PUT_LINE('Low stock alert created for batch ' || :NEW.batch_id);
    END IF;
END;
/
