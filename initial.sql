DROP DATABASE IF EXISTS coffee_shop;
CREATE DATABASE coffee_shop;
USE coffee_shop;

-- Account
CREATE TABLE Account (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(100),
    role ENUM('staff', 'manager', 'admin')
);

-- Staff
CREATE TABLE Staff (
    staffId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    position VARCHAR(50),
    username VARCHAR(50),
    FOREIGN KEY (username) REFERENCES Account(username)
);

-- Manager (là một phần của staff)
CREATE TABLE Manager (
    staffId INT PRIMARY KEY,
    FOREIGN KEY (staffId) REFERENCES Staff(staffId)
);

-- Customer
CREATE TABLE Customer (
    customerId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    points INT
);

-- Item
CREATE TABLE Item (
    itemId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    image VARCHAR(255),
    note TEXT,
    price INT
);

-- Inventory
CREATE TABLE Inventory (
    itemId INT PRIMARY KEY,
    quantity INT,
    FOREIGN KEY (itemId) REFERENCES Item(itemId)
);

-- Order
CREATE TABLE `Order` (
    orderId INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('success', 'failed', 'pending') default 'pending',
    date DATE,
    tableNumber VARCHAR(10),
    staffId INT,
    customerId INT,
    FOREIGN KEY (staffId) REFERENCES Staff(staffId),
    FOREIGN KEY (customerId) REFERENCES Customer(customerId)
);

-- include (Order - Item)
CREATE TABLE OrderIncludeItem (
    orderId INT,
    itemId INT,
    quantity INT,
    PRIMARY KEY (orderId, itemId),
    FOREIGN KEY (orderId) REFERENCES `Order`(orderId),
    FOREIGN KEY (itemId) REFERENCES Item(itemId)
);

-- Promotion
CREATE TABLE Promotion (
    promotionId INT AUTO_INCREMENT PRIMARY KEY,
    discountCode VARCHAR(50),
    discountRate DECIMAL(5,2),
    startDate DATE,
    endDate DATE
);

-- Payment
CREATE TABLE Payment (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    method VARCHAR(50),
    status VARCHAR(20),
    totalAmount INT,
    orderId INT,
    promotionId INT,
    FOREIGN KEY (orderId) REFERENCES `Order`(orderId),
    FOREIGN KEY (promotionId) REFERENCES Promotion(promotionId)
);



INSERT INTO Account (username, password, role) VALUES
('johnstaff', 'hashed_pwd1', 'staff'),
('manager01', 'hashed_pwd2', 'manager'),
('admin99', 'hashed_pwd3', 'admin'),
('lucystaff', 'hashed_pwd4', 'staff'),
('bobmanager', 'hashed_pwd5', 'manager');

INSERT INTO Staff (name, position, username) VALUES
('John Doe', 'Barista', 'johnstaff'),
('Lucy Smith', 'Cashier', 'lucystaff'),
('Bob Manager', 'Manager', 'bobmanager'),
('Anna Waiter', 'Waiter', 'manager01');

-- Bob Manager and Anna Waiter are both managers
INSERT INTO Manager (staffId) VALUES
(3),
(4);


INSERT INTO Customer (name, phone, points) VALUES
('Alice', '1234567890', 10),
('Bob', '0987654321', 25),
('Charlie', '1112223333', 5),
('Diana', '2223334444', 50);

INSERT INTO Item (name, image, note, price) VALUES
('Espresso', 'espresso.jpg', 'Strong and bold', 30000),
('Latte', 'latte.jpg', 'Milk and coffee', 35000),
('Cappuccino', 'cappuccino.jpg', 'With foam', 35000),
('Mocha', 'mocha.jpg', 'Chocolate blend', 40000),
('Croissant', 'croissant.jpg', 'Freshly baked', 25000);

INSERT INTO Inventory (itemId, quantity) VALUES
(1, 100),
(2, 80),
(3, 60),
(4, 50),
(5, 40);

INSERT INTO `Order` (status, date, tableNumber, staffId, customerId) VALUES
('pending', '2025-05-10', 'T1', 1, 1),
('pending', '2025-05-10', 'T2', 2, 2),
('pending', '2025-05-09', 'T3', 3, 3),
('pending', '2025-05-08', 'T1', 1, 4);

INSERT INTO OrderIncludeItem (orderId, itemId, quantity) VALUES
(1, 1, 2),
(1, 5, 1),
(2, 2, 1),
(2, 3, 1),
(3, 4, 1),
(4, 1, 1),
(4, 2, 1),
(4, 3, 1);

INSERT INTO Promotion (discountCode, discountRate, startDate, endDate) VALUES
('DISC10', 10.00, '2025-05-01', '2025-05-31'),
('SAVE15', 15.00, '2025-05-05', '2025-06-05'),
('OFF5', 5.00, '2025-04-01', '2025-04-30');

INSERT INTO Payment (method, status, totalAmount, orderId, promotionId) VALUES
('cash', 'completed', 85000, 1, 2),
('card', 'completed', 70000, 2, 3),
('cash', 'cancelled', 40000, 3, 1),
('momo', 'completed', 105000, 4, 3);


