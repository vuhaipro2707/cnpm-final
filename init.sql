DROP DATABASE IF EXISTS coffee_shop;
CREATE DATABASE coffee_shop;
USE coffee_shop;

-- Account
CREATE TABLE Account (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(100),
    role ENUM('staff', 'manager', 'admin', 'customer'),
    avatar VARCHAR(255)
);

-- Staff
CREATE TABLE Staff (
    staffId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    position VARCHAR(50),
    username VARCHAR(50),
    isManager TINYINT(1) DEFAULT 0,
    salary INT,
    phone VARCHAR(20),
    FOREIGN KEY (username) REFERENCES Account(username)
);



-- Customer
CREATE TABLE Customer (
    customerId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20),
    points INT,
    username VARCHAR(50),
    FOREIGN KEY (username) REFERENCES Account(username)
);

-- Item
CREATE TABLE Item (
    itemId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    image VARCHAR(255),
    note TEXT,
    price INT,
    type VARCHAR(100)
);

-- Inventory
CREATE TABLE Inventory (
    itemId INT PRIMARY KEY,
    quantity INT,
    FOREIGN KEY (itemId) REFERENCES Item(itemId)
);

CREATE TABLE TableLayout (
    layoutPosition VARCHAR(10) PRIMARY KEY, -- ví dụ: '3_5' cho hàng 3, cột 5
    tableNumber VARCHAR(10),
    status ENUM('empty', 'serving', 'paid', 'inactive') DEFAULT 'empty'
);

-- Order
CREATE TABLE `Order` (
    orderId INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('paid', 'success', 'failed', 'pending') default 'pending',
    date DATETIME,
    layoutPosition VARCHAR(10),
    tableNumber VARCHAR(10),
    customerId INT,
    FOREIGN KEY (customerId) REFERENCES Customer(customerId),
    FOREIGN KEY (layoutPosition) REFERENCES TableLayout(layoutPosition)
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
    discountRate INT,
    startDate DATE,
    endDate DATE,
    active TINYINT(1)
);

-- Payment
CREATE TABLE Payment (
    paymentId INT AUTO_INCREMENT PRIMARY KEY,
    method ENUM('cash', 'transfer', 'momo', 'qr'),
    status ENUM('completed', 'pending') default 'pending',
    totalAmount INT,
    pointsApplied INT,
    pointsBonus INT,
    orderId INT UNIQUE,
    promotionId INT,
    FOREIGN KEY (orderId) REFERENCES `Order`(orderId),
    FOREIGN KEY (promotionId) REFERENCES Promotion(promotionId)
);






INSERT INTO Account (username, password, role) VALUES
('staff', '1', 'staff'),
('manager', '1', 'manager'),
('admin99', '1', 'admin'),
('lucystaff', '1', 'staff'),
('bobmanager', '1', 'manager'),
('customer', '1', 'customer'),
('customerbob', '1', 'customer'),
('customercharlie', '1', 'customer'),
('customerdiana', '1', 'customer');

INSERT INTO Staff (name, position, username, isManager, salary, phone) VALUES
('John Doe', 'Barista', 'staff', 0, 3000, '0123456789'),
('Lucy Smith', 'Waiter', 'lucystaff', 0, 2800, '0987654321'),
('Bob Manager', 'Cashier', 'bobmanager', 1, 4000, '0111222333'),
('Anna Waiter', 'Waiter', 'manager', 1, 3500, '0222333444');


INSERT INTO Customer (name, phone, points, username) VALUES
('Alice', '1234567890', 10, 'customer'),
('Bob', '0987654321', 25, 'customerbob'),
('Charlie', '1112223333', 5, 'customercharlie'),
('Diana', '2223334444', 50, 'customerdiana');

INSERT INTO Item (name, image, note, price, type) VALUES
('Espresso', 'espresso.jpg', 'Strong and bold', 30000, 'Đồ uống'),
('Latte', 'latte.jpg', 'Milk and coffee', 35000, 'Đồ uống'),
('Cappuccino', 'cappuccino.jpg', 'With foam', 35000, 'Đồ uống'),
('Mocha', 'mocha.jpg', 'Chocolate blend', 40000, 'Đồ uống'),
('Americano', 'americano.jpg', 'Light and smooth', 30000, 'Đồ uống'),
('Macchiato', 'macchiato.jpg', 'Espresso with milk foam', 38000, 'Đồ uống'),
('Matcha Latte', 'matcha_latte.jpg', 'Green tea blend', 40000, 'Đồ uống'),
('Trà đào', 'tradao.jpg', 'Đào ngâm tươi', 32000, 'Đồ uống'),
('Sinh tố bơ', 'sinhtobo.jpg', 'Béo ngậy, mát lạnh', 35000, 'Đồ uống'),
('Nước chanh', 'nuocchanh.jpg', 'Giải khát tốt', 25000, 'Đồ uống'),

('Croissant', 'croissant.jpg', 'Freshly baked', 25000, 'Bánh ngọt'),
('Donut', 'donut.jpg', 'Sweet and soft', 20000, 'Bánh ngọt'),
('Muffin', 'muffin.jpg', 'Chocolate chips', 22000, 'Bánh ngọt'),
('Tiramisu', 'tiramisu.jpg', 'Coffee-flavored cake', 45000, 'Bánh ngọt'),
('Cheesecake', 'cheesecake.jpg', 'Creamy and smooth', 50000, 'Bánh ngọt'),

('Khoai tây chiên', 'khoaitay.jpg', 'Giòn rụm', 28000, 'Đồ ăn vặt'),
('Bánh tráng trộn', 'banhtrangtron.jpg', 'Đặc sản Sài Gòn', 25000, 'Đồ ăn vặt'),
('Nem chua rán', 'nemchua.jpg', 'Chiên giòn, chấm tương ớt', 30000, 'Đồ ăn vặt'),
('Phô mai que', 'phomaique.jpg', 'Kéo sợi hấp dẫn', 27000, 'Đồ ăn vặt'),
('Xúc xích chiên', 'xucxich.jpg', 'Nóng hổi', 30000, 'Đồ ăn vặt');


INSERT INTO Inventory (itemId, quantity) VALUES
(1, 100),  -- Espresso
(2, 80),   -- Latte
(3, 60),   -- Cappuccino
(4, 50),   -- Mocha
(5, 40),   -- Croissant
(6, 120),  -- Donut
(7, 150),  -- Muffin
(8, 110),  -- Tiramisu
(9, 130),  -- Cheesecake
(10, 90),  -- Khoai tây chiên
(11, 140), -- Bánh tráng trộn
(12, 100), -- Nem chua rán
(13, 95),  -- Phô mai que
(14, 120), -- Xúc xích chiên
(15, 85),  -- Trà đào
(16, 110), -- Sinh tố bơ
(17, 100), -- Matcha Latte
(18, 130), -- Americano
(19, 75),  -- Macchiato
(20, 160); -- Nước chanh

INSERT INTO TableLayout (tableNumber, layoutPosition, status) VALUES
('T1', '0_0', 'serving'),
('T2', '0_1', 'serving'),
('T3', '1_0', 'serving'),
('T4', '1_1', 'serving');


INSERT INTO `Order` (status, date, tableNumber, layoutPosition, customerId) VALUES
('pending', '2025-05-10 12:00:00', 'T1', '0_0', 1),
('pending', '2025-05-10 13:00:00', 'T2', '0_1', 2),
('pending', '2025-05-09 18:30:00', 'T3', '1_0', 3),
('pending', '2025-05-08 19:15:00', 'T4', '1_1', 4);

INSERT INTO OrderIncludeItem (orderId, itemId, quantity) VALUES
(1, 1, 2),
(1, 5, 1),
(2, 2, 1),
(2, 3, 1),
(3, 4, 1),
(4, 1, 1),
(4, 2, 1),
(4, 3, 1);

INSERT INTO Promotion (discountCode, discountRate, startDate, endDate, active) VALUES
('DISC10', 10, '2025-05-01', '2025-05-31', 1),
('SAVE15', 15, '2025-05-05', '2025-06-05', 1),
('OFF5', 5, '2025-04-01', '2025-04-30', 1);



