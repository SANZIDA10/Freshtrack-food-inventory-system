-- MySQL-compatible query examples

SELECT p.product_id, p.product_name, c.category_name, p.unit_of_measure, p.reorder_level
FROM products p
JOIN categories c ON p.category_id = c.category_id
ORDER BY p.product_name;

SELECT p.product_name, COALESCE(SUM(b.quantity_available), 0) AS total_available_stock
FROM products p
LEFT JOIN batches b ON p.product_id = b.product_id
GROUP BY p.product_name
ORDER BY total_available_stock DESC;

SELECT b.batch_code, p.product_name, b.expiry_date, b.quantity_available
FROM batches b
JOIN products p ON b.product_id = p.product_id
WHERE b.expiry_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)
ORDER BY b.expiry_date;

SELECT s.supplier_name, COUNT(pu.purchase_id) AS purchase_count, SUM(pu.total_amount) AS total_value
FROM suppliers s
JOIN purchases pu ON s.supplier_id = pu.supplier_id
GROUP BY s.supplier_name
ORDER BY total_value DESC;

SELECT p.product_name
FROM products p
WHERE p.product_id IN (
    SELECT b.product_id
    FROM batches b
    GROUP BY b.product_id
    HAVING COALESCE(SUM(b.quantity_available), 0) < (
        SELECT MAX(p2.reorder_level)
        FROM products p2
    )
);

SELECT p.product_name AS item_name, 'EXPIRING' AS record_type
FROM products p
WHERE p.product_id IN (
    SELECT b.product_id
    FROM batches b
    WHERE b.expiry_date <= DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY)
)
UNION
SELECT p.product_name AS item_name, 'DONATED' AS record_type
FROM products p
WHERE p.product_id IN (
    SELECT b.product_id
    FROM batches b
    JOIN donations d ON b.batch_id = d.batch_id
);
