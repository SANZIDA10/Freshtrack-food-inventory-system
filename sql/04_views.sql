
CREATE OR REPLACE VIEW v_inventory_overview AS
SELECT p.product_id,
	   p.product_name,
	   c.category_name,
	   NVL(SUM(b.quantity_available), 0) AS total_available_stock,
	   MIN(b.expiry_date) AS nearest_expiry_date
FROM products p
JOIN categories c ON p.category_id = c.category_id
LEFT JOIN batches b ON p.product_id = b.product_id
GROUP BY p.product_id, p.product_name, c.category_name;

CREATE OR REPLACE VIEW v_expiring_soon AS
SELECT b.batch_id,
	   b.batch_code,
	   p.product_name,
	   b.quantity_available,
	   b.expiry_date
FROM batches b
JOIN products p ON b.product_id = p.product_id
WHERE b.expiry_date BETWEEN SYSDATE AND SYSDATE + 7;

CREATE OR REPLACE VIEW v_consume_first AS
SELECT b.batch_id,
	   b.batch_code,
	   p.product_name,
	   b.quantity_available,
	   b.expiry_date,
	   CASE
		   WHEN b.expiry_date <= SYSDATE + 3 THEN 'HIGH PRIORITY'
		   WHEN b.expiry_date <= SYSDATE + 7 THEN 'MEDIUM PRIORITY'
		   ELSE 'NORMAL'
	   END AS consume_priority
FROM batches b
JOIN products p ON b.product_id = p.product_id;

CREATE OR REPLACE VIEW v_supplier_purchase_summary AS
SELECT s.supplier_name,
	   COUNT(pu.purchase_id) AS total_purchases,
	   SUM(pu.total_amount) AS total_purchase_value
FROM suppliers s
JOIN purchases pu ON s.supplier_id = pu.supplier_id
GROUP BY s.supplier_name;

CREATE OR REPLACE VIEW v_waste_summary AS
SELECT p.product_name,
	   SUM(wr.quantity_wasted) AS total_wasted_quantity
FROM waste_records wr
JOIN batches b ON wr.batch_id = b.batch_id
JOIN products p ON b.product_id = p.product_id
GROUP BY p.product_name;

