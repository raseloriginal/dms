-- Create Database
CREATE DATABASE IF NOT EXISTS dms_db;
USE dms_db;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    icon VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_no VARCHAR(20) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    delivery_fee DECIMAL(10, 2) DEFAULT 2.00,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    qty INT NOT NULL,
    icon VARCHAR(100) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Seed Products
INSERT INTO products (name, price, category, icon) VALUES
('Red Apple', 1.20, 'Fruits', 'fa-apple-whole'),
('Banana Bunch', 0.99, 'Fruits', 'fa-lemon'),
('Mango', 2.50, 'Fruits', 'fa-seedling'),
('Strawberries', 3.49, 'Fruits', 'fa-leaf'),
('Watermelon', 4.99, 'Fruits', 'fa-lemon'),
('Broccoli', 1.80, 'Vegetables', 'fa-tree'),
('Carrot', 0.89, 'Vegetables', 'fa-carrot'),
('Tomato', 1.10, 'Vegetables', 'fa-pepper-hot'),
('Spinach', 2.20, 'Vegetables', 'fa-leaf'),
('Avocado', 1.75, 'Vegetables', 'fa-seedling'),
('Fresh Milk', 2.99, 'Dairy', 'fa-bottle-water'),
('Cheese Block', 4.50, 'Dairy', 'fa-cheese'),
('Greek Yogurt', 3.20, 'Dairy', 'fa-bowl-food'),
('Butter', 2.79, 'Dairy', 'fa-cube'),
('Sourdough Bread', 3.99, 'Bakery', 'fa-bread-slice'),
('Croissant', 1.99, 'Bakery', 'fa-cookie'),
('Muffin', 1.49, 'Bakery', 'fa-cake-candles'),
('Orange Juice', 3.29, 'Beverages', 'fa-glass-water'),
('Green Tea', 2.49, 'Beverages', 'fa-mug-hot'),
('Sparkling Water', 1.59, 'Beverages', 'fa-bottle-droplet');

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Admin (password: admin123)
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');
