-- Description: This file contains the SQL statements for creating the database and tables, and inserting sample data into the tables.

-- Drop the database if it already exists
DROP DATABASE IF EXISTS restaurant_db;

-- Create the database
CREATE DATABASE restaurant_db;

-- Use the database
USE restaurant_db;


-- Create the tables
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role ENUM('customer', 'staff', 'manager') NOT NULL
);

CREATE TABLE customer (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    address VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE staff (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE manager (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TABLE menu (
    menu_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL
);

CREATE TABLE menu_item (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(8, 2) NOT NULL,
    FOREIGN KEY (menu_id) REFERENCES menu (menu_id)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    order_date DATETIME NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer (user_id),
    FOREIGN KEY (menu_id) REFERENCES menu (menu_id)
);

CREATE TABLE reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    num_guests INT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer (user_id)
);

CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reservation_id INT NOT NULL,
    rating INT NOT NULL,
    comment VARCHAR(200),
    FOREIGN KEY (customer_id) REFERENCES customer (user_id),
    FOREIGN KEY (reservation_id) REFERENCES reservations (reservation_id)
);

CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    reservation_id INT NOT NULL,
    amount DECIMAL(8, 2) NOT NULL,
    payment_date DATETIME NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer (user_id),
    FOREIGN KEY (reservation_id) REFERENCES reservations (reservation_id)
);

CREATE TABLE restaurant (
    restaurant_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    opening_hours VARCHAR(100) NOT NULL
);

-- Insert sample data into the tables
INSERT INTO users (username, password, role) VALUES
    ('test', 'test', 'manager'),
    ('sarahanderson', 'testpass', 'staff'),
    ('robertbrown', 'testpass', 'staff'),
    ('amydavis', 'testpass', 'staff'),
    ('jenniferadams', 'testpass', 'manager'),
    ('johnsmith', 'testpass', 'customer'),
    ('sarahjohnson', 'testpass', 'customer'),
    ('michaelwilliams', 'testpass', 'customer'),
    ('emilydavis', 'testpass', 'customer'),
    ('davidwilson', 'testpass', 'customer');

INSERT INTO customer (user_id, full_name, email, phone_number, address) VALUES
    (1, 'John Smith', 'johnsmith@example.com', '5551234567', '123 Main St'),
    (2, 'Sarah Johnson', 'sarahjohnson@example.com', '5559876543', '456 Elm St'),
    (3, 'Michael Williams', 'michaelwilliams@example.com', '5554567890', '789 Oak St'),
    (4, 'Emily Davis', 'emilydavis@example.com', '7778889999', '321 Pine St'),
    (5, 'David Wilson', 'davidwilson@example.com', '4445556666', '654 Maple St'),
    (6, 'Sarah Anderson', 'sarahanderson@example.com', '5551234567', '123 Main St');


INSERT INTO staff (user_id, full_name, position) VALUES
    (6, 'Sarah Anderson', 'Waiter'),
    (7, 'Robert Brown', 'Chef'),
    (8, 'Amy Davis', 'Bartender');

INSERT INTO manager (user_id, full_name) VALUES
    (9, 'Jennifer Adams');

INSERT INTO menu (name, description) VALUES
    ('Breakfast Menu', 'Delicious breakfast options'),
    ('Lunch Menu', 'Variety of lunch items'),
    ('Dinner Menu', 'Elegant dinner choices');

INSERT INTO menu_item (menu_id, name, price) VALUES
    (1, 'Pancakes', 8.99),
    (1, 'Eggs Benedict', 12.99),
    (2, 'Caesar Salad', 9.99),
    (2, 'Club Sandwich', 11.99),
    (3, 'Grilled Salmon', 18.99),
    (3, 'Filet Mignon', 29.99);

INSERT INTO orders (customer_id, menu_id, quantity, order_date) VALUES
    (1, 2, 2, '2023-06-10 18:30:00'),
    (2, 1, 1, '2023-06-11 13:00:00'),
    (3, 3, 3, '2023-06-12 20:15:00'),
    (4, 2, 2, '2023-06-13 17:30:00'),
    (5, 1, 2, '2023-06-14 16:30:00');

INSERT INTO reservations (customer_id, reservation_date, num_guests) VALUES
    (1, '2023-06-10 19:00:00', 2),
    (2, '2023-06-11 20:00:00', 4),
    (3, '2023-06-12 21:00:00', 3),
    (4, '2023-06-13 18:00:00', 2),
    (5, '2023-06-14 17:30:00', 5);

INSERT INTO feedback (customer_id, reservation_id, rating, comment) VALUES
    (1, 1, 4, 'Great experience!'),
    (2, 2, 5, 'Excellent service and food'),
    (3, 3, 3, 'Average experience'),
    (4, 4, 4, 'Friendly staff'),
    (5, 5, 2, 'Disappointing quality');

INSERT INTO payments (customer_id, reservation_id, amount, payment_date) VALUES
    (1, 1, 50.00, '2023-06-10 19:00:00'),
    (2, 2, 30.00, '2023-06-11 20:00:00'),
    (3, 3, 90.00, '2023-06-12 21:00:00'),
    (4, 4, 40.00, '2023-06-13 18:00:00'),
    (5, 5, 60.00, '2023-06-14 17:30:00');

INSERT INTO restaurant (name, address, phone_number, opening_hours) VALUES
    ('Delicious Eats', '123 Main St', '5551234567', '9:00 AM - 10:00 PM');


SELECT * FROM users;
SELECT * FROM customer;
SELECT * FROM staff;
SELECT * FROM manager;
SELECT * FROM menu;
SELECT * FROM menu_item;
SELECT * FROM orders;
SELECT * FROM reservations;
SELECT * FROM feedback;
SELECT * FROM payments;
SELECT * FROM restaurant;