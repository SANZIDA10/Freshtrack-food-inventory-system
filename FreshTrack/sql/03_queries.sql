
-- 1. Basic product list with category name.
SELECT p.product_id, p.product_name, c.category_name, p.unit_of_measure, p.reorder_level
FROM products p
JOIN categories c ON p.category_id = c.category_id
ORDER BY p.product_name;

-- 2. Available stock by product.
SELECT p.product_name, SUM(b.quantity_available) AS total_available_stock
FROM products p
JOIN batches b ON p.product_id = b.product_id
GROUP BY p.product_name
ORDER BY total_available_stock DESC;

-- 3. Batches expiring within 7 days.
SELECT b.batch_code, p.product_name, b.expiry_date, b.quantity_available
FROM batches b
JOIN products p ON b.product_id = p.product_id
WHERE b.expiry_date BETWEEN SYSDATE AND SYSDATE + 7
ORDER BY b.expiry_date;

-- 4. Suppliers with completed purchases.
SELECT s.supplier_name, COUNT(pu.purchase_id) AS purchase_count, SUM(pu.total_amount) AS total_value
FROM suppliers s
JOIN purchases pu ON s.supplier_id = pu.supplier_id
GROUP BY s.supplier_name
ORDER BY total_value DESC;

-- 5. Low stock items using a subquery.
SELECT p.product_name
FROM products p
WHERE p.product_id IN (
	SELECT b.product_id
	FROM batches b
	GROUP BY b.product_id
	HAVING SUM(b.quantity_available) < (
		SELECT MAX(p2.reorder_level)
		FROM products p2
	)
);

-- 6. Expiring items compared with donated items using set operations.
SELECT p.product_name AS item_name, 'EXPIRING' AS record_type
FROM products p
WHERE p.product_id IN (
	SELECT b.product_id
	FROM batches b
	WHERE b.expiry_date <= SYSDATE + 5
)
UNION
SELECT p.product_name AS item_name, 'DONATED' AS record_type
FROM products p
WHERE p.product_id IN (
	SELECT b.product_id
	FROM batches b
	JOIN donations d ON b.batch_id = d.batch_id
);

-- 7. Join report for batch, product, purchase, and supplier.
SELECT b.batch_code, p.product_name, s.supplier_name, pu.purchase_date, b.quantity_available, b.expiry_date
FROM batches b
JOIN products p ON b.product_id = p.product_id
JOIN purchases pu ON b.purchase_id = pu.purchase_id
JOIN suppliers s ON pu.supplier_id = s.supplier_id
ORDER BY b.expiry_date;

-- 8. Count of alerts by type.
SELECT alert_type, COUNT(*) AS alert_count
FROM alerts
GROUP BY alert_type
ORDER BY alert_count DESC;

