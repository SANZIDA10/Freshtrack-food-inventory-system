-- Sample seed data for MySQL

INSERT INTO categories (category_name, description) VALUES
('Dairy', 'Milk and dairy products'),
('Bakery', 'Bread, cake, and baked items'),
('Vegetables', 'Fresh produce items');

INSERT INTO suppliers (supplier_name, contact_person, phone, email, address) VALUES
('Fresh Farm Suppliers', 'Rakib Hasan', '01711111111', 'freshfarm@example.com', 'Dhaka'),
('Daily Needs Trading', 'Nusrat Jahan', '01722222222', 'dailyneeds@example.com', 'Chattogram');

INSERT INTO users (full_name, email, phone, role) VALUES
('Admin User', 'admin@freshtrack.com', '01900000001', 'ADMIN'),
('Inventory Manager', 'manager@freshtrack.com', '01900000002', 'MANAGER');

INSERT INTO products (category_id, product_name, brand, unit_of_measure, shelf_life_days, reorder_level) VALUES
(1, 'Milk 1L', 'Prime', 'Packet', 7, 10),
(2, 'Whole Wheat Bread', 'FreshBake', 'Piece', 5, 15),
(3, 'Tomato', 'Local Farm', 'Kg', 6, 20);

INSERT INTO purchases (supplier_id, purchased_by, purchase_date, invoice_no, total_amount, status) VALUES
(1, 1, CURRENT_DATE, 'INV-1001', 2500.00, 'COMPLETED'),
(2, 2, CURRENT_DATE, 'INV-1002', 1800.00, 'COMPLETED');

INSERT INTO batches (purchase_id, product_id, batch_code, manufacture_date, expiry_date, quantity_received, quantity_available, unit_cost, storage_location, batch_status) VALUES
(1, 1, 'MILK-B001', DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 50, 50, 45.00, 'Cold Storage A', 'IN_STOCK'),
(2, 2, 'BREAD-B001', DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY), 30, 30, 35.00, 'Shelf 1', 'IN_STOCK'),
(2, 3, 'VEG-B001', DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY), 40, 40, 20.00, 'Chiller B', 'IN_STOCK');

INSERT INTO inventory_movements (batch_id, user_id, movement_type, quantity, reference_type, reference_id, notes) VALUES
(1, 2, 'IN', 50, 'PURCHASE', 1, 'Initial stock entry'),
(2, 2, 'OUT', 5, 'SALE', 101, 'Sold to customer');

INSERT INTO donations (batch_id, approved_by, organization_name, quantity_donated, status) VALUES
(2, 1, 'Local Food Bank', 10, 'APPROVED');

INSERT INTO waste_records (batch_id, recorded_by, quantity_wasted, reason) VALUES
(3, 2, 3, 'Items damaged during transport');

INSERT INTO alerts (batch_id, alert_type, status, message) VALUES
(2, 'EXPIRY', 'OPEN', 'Bread batch is expiring soon');
