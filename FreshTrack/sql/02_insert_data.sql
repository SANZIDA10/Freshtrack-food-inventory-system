INSERT INTO categories (category_name, description) VALUES ('Dairy', 'Milk and dairy products');
INSERT INTO categories (category_name, description) VALUES ('Bakery', 'Bread, cake, and baked items');
INSERT INTO categories (category_name, description) VALUES ('Vegetables', 'Fresh produce items');

INSERT INTO suppliers (supplier_name, contact_person, phone, email, address)
VALUES ('Fresh Farm Suppliers', 'Rakib Hasan', '01711111111', 'freshfarm@example.com', 'Dhaka');

INSERT INTO suppliers (supplier_name, contact_person, phone, email, address)
VALUES ('Daily Needs Trading', 'Nusrat Jahan', '01722222222', 'dailyneeds@example.com', 'Chattogram');

INSERT INTO users (full_name, email, phone, role)
VALUES ('Admin User', 'admin@freshtrack.com', '01900000001', 'ADMIN');

INSERT INTO users (full_name, email, phone, role)
VALUES ('Inventory Manager', 'manager@freshtrack.com', '01900000002', 'MANAGER');

INSERT INTO products (category_id, product_name, brand, unit_of_measure, shelf_life_days, reorder_level)
VALUES (1, 'Milk 1L', 'Prime', 'Packet', 7, 10);

INSERT INTO products (category_id, product_name, brand, unit_of_measure, shelf_life_days, reorder_level)
VALUES (2, 'Whole Wheat Bread', 'FreshBake', 'Piece', 5, 15);

INSERT INTO products (category_id, product_name, brand, unit_of_measure, shelf_life_days, reorder_level)
VALUES (3, 'Tomato', 'Local Farm', 'Kg', 6, 20);

INSERT INTO purchases (supplier_id, purchased_by, purchase_date, invoice_no, total_amount, status)
VALUES (1, 1, SYSDATE, 'INV-1001', 2500, 'COMPLETED');

INSERT INTO purchases (supplier_id, purchased_by, purchase_date, invoice_no, total_amount, status)
VALUES (2, 2, SYSDATE, 'INV-1002', 1800, 'COMPLETED');

INSERT INTO batches (purchase_id, product_id, batch_code, manufacture_date, expiry_date, quantity_received, quantity_available, unit_cost, storage_location, batch_status)
VALUES (1, 1, 'MILK-B001', SYSDATE - 1, SYSDATE + 5, 50, 50, 45, 'Cold Storage A', 'IN_STOCK');

INSERT INTO batches (purchase_id, product_id, batch_code, manufacture_date, expiry_date, quantity_received, quantity_available, unit_cost, storage_location, batch_status)
VALUES (2, 2, 'BREAD-B001', SYSDATE - 1, SYSDATE + 2, 30, 30, 35, 'Shelf 1', 'IN_STOCK');

INSERT INTO batches (purchase_id, product_id, batch_code, manufacture_date, expiry_date, quantity_received, quantity_available, unit_cost, storage_location, batch_status)
VALUES (2, 3, 'VEG-B001', SYSDATE - 1, SYSDATE + 4, 40, 40, 20, 'Chiller B', 'IN_STOCK');

INSERT INTO inventory_movements (batch_id, user_id, movement_type, quantity, reference_type, reference_id, notes)
VALUES (1, 2, 'IN', 50, 'PURCHASE', 1, 'Initial stock entry');

INSERT INTO inventory_movements (batch_id, user_id, movement_type, quantity, reference_type, reference_id, notes)
VALUES (2, 2, 'OUT', 5, 'SALE', 101, 'Sold to customer');

INSERT INTO donations (batch_id, approved_by, organization_name, quantity_donated, status)
VALUES (2, 1, 'Local Food Bank', 10, 'APPROVED');

INSERT INTO waste_records (batch_id, recorded_by, quantity_wasted, reason)
VALUES (3, 2, 3, 'Items damaged during transport');

INSERT INTO alerts (batch_id, alert_type, status, message)
VALUES (2, 'EXPIRY', 'OPEN', 'Bread batch is expiring soon');

COMMIT;

