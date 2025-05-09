CREATE TABLE `coffee_shop`.`inventory` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `note` TEXT NOT NULL,
    `price` INT NOT NULL,
    `quantity` INT NOT NULL,
    PRIMARY KEY (`id`)
);


INSERT INTO `coffee_shop`.`inventory` (`name`, `image`, `note`, `price`, `quantity`)
VALUES
('Cà phê sữa', 'uploads/caphe_sua.jpg', 'Cà phê pha với sữa đặc, đậm vị truyền thống', 25000, 20),
('Trà đào', 'uploads/tra_dao.jpg', 'Trà đào thơm mát, có miếng đào thật', 30000, 15),
('Bạc xỉu', 'uploads/bac_xiu.jpg', 'Sữa nhiều hơn cà phê, vị ngọt dịu', 27000, 18),
('Espresso', 'uploads/espresso.jpg', 'Cà phê Ý đậm đặc, ly nhỏ nhưng chất', 35000, 10);
