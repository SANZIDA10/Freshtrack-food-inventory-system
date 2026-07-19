-- MySQL-compatible schema for FreshTrack
-- Run this script in a MySQL database.

SET NAMES utf8mb4;

DROP TABLE IF EXISTS alerts;
DROP TABLE IF EXISTS waste_records;
DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS inventory_movements;
DROP TABLE IF EXISTS batches;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(120) NOT NULL,
    contact_person VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(120) NULL UNIQUE,
    address VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(20) NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'STAFF',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_users_role CHECK (role IN ('ADMIN', 'MANAGER', 'STAFF'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(120) NOT NULL,
    brand VARCHAR(100) NULL,
    unit_of_measure VARCHAR(30) NOT NULL,
    shelf_life_days INT NOT NULL,
    reorder_level INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (category_id),
    CONSTRAINT uq_products_name UNIQUE (category_id, product_name),
    CONSTRAINT ck_products_shelf_life CHECK (shelf_life_days > 0),
    CONSTRAINT ck_products_reorder_level CHECK (reorder_level >= 0),
    CONSTRAINT ck_products_status CHECK (status IN ('ACTIVE', 'INACTIVE'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    purchased_by INT NOT NULL,
    purchase_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    invoice_no VARCHAR(50) NULL UNIQUE,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'COMPLETED',
    CONSTRAINT fk_purchases_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers (supplier_id),
    CONSTRAINT fk_purchases_user FOREIGN KEY (purchased_by) REFERENCES users (user_id),
    CONSTRAINT ck_purchases_total CHECK (total_amount >= 0),
    CONSTRAINT ck_purchases_status CHECK (status IN ('COMPLETED', 'PENDING', 'CANCELLED'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE batches (
    batch_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    product_id INT NOT NULL,
    batch_code VARCHAR(50) NOT NULL UNIQUE,
    manufacture_date DATE NULL,
    expiry_date DATE NOT NULL,
    quantity_received INT NOT NULL,
    quantity_available INT NOT NULL,
    unit_cost DECIMAL(12,2) NOT NULL,
    storage_location VARCHAR(100) NULL,
    batch_status VARCHAR(20) NOT NULL DEFAULT 'IN_STOCK',
    CONSTRAINT fk_batches_purchase FOREIGN KEY (purchase_id) REFERENCES purchases (purchase_id),
    CONSTRAINT fk_batches_product FOREIGN KEY (product_id) REFERENCES products (product_id),
    CONSTRAINT ck_batches_quantity_received CHECK (quantity_received > 0),
    CONSTRAINT ck_batches_quantity_available CHECK (quantity_available >= 0),
    CONSTRAINT ck_batches_unit_cost CHECK (unit_cost >= 0),
    CONSTRAINT ck_batches_status CHECK (batch_status IN ('IN_STOCK', 'LOW_STOCK', 'EXPIRED', 'DONATED', 'WASTED'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE inventory_movements (
    movement_id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    user_id INT NOT NULL,
    movement_type VARCHAR(20) NOT NULL,
    movement_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quantity INT NOT NULL,
    reference_type VARCHAR(30) NULL,
    reference_id INT NULL,
    notes VARCHAR(255) NULL,
    CONSTRAINT fk_movements_batch FOREIGN KEY (batch_id) REFERENCES batches (batch_id),
    CONSTRAINT fk_movements_user FOREIGN KEY (user_id) REFERENCES users (user_id),
    CONSTRAINT ck_movements_type CHECK (movement_type IN ('IN', 'OUT', 'ADJUST', 'DONATE', 'WASTE')),
    CONSTRAINT ck_movements_quantity CHECK (quantity > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE donations (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    approved_by INT NOT NULL,
    donation_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    organization_name VARCHAR(150) NOT NULL,
    quantity_donated INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'APPROVED',
    CONSTRAINT fk_donations_batch FOREIGN KEY (batch_id) REFERENCES batches (batch_id),
    CONSTRAINT fk_donations_user FOREIGN KEY (approved_by) REFERENCES users (user_id),
    CONSTRAINT ck_donations_quantity CHECK (quantity_donated > 0),
    CONSTRAINT ck_donations_status CHECK (status IN ('APPROVED', 'PENDING', 'REJECTED'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE waste_records (
    waste_id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    recorded_by INT NOT NULL,
    waste_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    quantity_wasted INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    CONSTRAINT fk_waste_batch FOREIGN KEY (batch_id) REFERENCES batches (batch_id),
    CONSTRAINT fk_waste_user FOREIGN KEY (recorded_by) REFERENCES users (user_id),
    CONSTRAINT ck_waste_quantity CHECK (quantity_wasted > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE alerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    alert_type VARCHAR(30) NOT NULL,
    alert_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) NOT NULL DEFAULT 'OPEN',
    message VARCHAR(255) NOT NULL,
    CONSTRAINT fk_alerts_batch FOREIGN KEY (batch_id) REFERENCES batches (batch_id),
    CONSTRAINT ck_alerts_type CHECK (alert_type IN ('EXPIRY', 'LOW_STOCK', 'DONATION', 'WASTE')),
    CONSTRAINT ck_alerts_status CHECK (status IN ('OPEN', 'ACKNOWLEDGED', 'CLOSED'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
