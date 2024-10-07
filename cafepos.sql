-- Create Database
CREATE DATABASE IF NOT EXISTS cafepos;
USE cafepos;

-- Table structure for table `category`
CREATE TABLE `category` (
  `category_id` TINYINT(4) NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(30) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `ingredients`
CREATE TABLE `ingredients` (
  `ingredient_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `ingredient_name` VARCHAR(30) NOT NULL UNIQUE,
  `category_id` TINYINT(4) NOT NULL,
  `quantity` FLOAT NOT NULL,
  `unit` VARCHAR(5) NOT NULL,
  `total_price` FLOAT NOT NULL,
  `price_per_unit` FLOAT NOT NULL,
  `price_per_g_ml` FLOAT DEFAULT NULL,
  PRIMARY KEY (`ingredient_id`),
  FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `orders`
CREATE TABLE `orders` (
  `order_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `user_id` SMALLINT(6) NOT NULL,
  `total_amount` FLOAT NOT NULL,
  `tendered_amount` FLOAT NOT NULL,
  `change` FLOAT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `order_items`
CREATE TABLE `order_items` (
  `order_item_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `order_id` SMALLINT(6) NOT NULL,
  `product_id` SMALLINT(6) NOT NULL,
  `quantity` TINYINT(4) NOT NULL,
  `subtotal` FLOAT NOT NULL,
  `profit` FLOAT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_item_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `products`
CREATE TABLE `products` (
  `product_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(30) NOT NULL,
  `category_id` TINYINT(4) NOT NULL,
  `product_image` BLOB NOT NULL,
  `cost_price` FLOAT NOT NULL,
  `selling_price` FLOAT NOT NULL,
  `profit` FLOAT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `product_ingredients`
CREATE TABLE `product_ingredients` (
  `product_id` SMALLINT(6) NOT NULL,
  `ingredient_id` SMALLINT(6) NOT NULL,
  `quantity_required` FLOAT NOT NULL,
  `price_per_g_ml` FLOAT NOT NULL,
  PRIMARY KEY (`product_id`, `ingredient_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`),
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `sales`
CREATE TABLE `sales` (
  `sale_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `order_id` SMALLINT(6) NOT NULL,
  `total_amount` FLOAT NOT NULL,
  `profit` FLOAT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `user`
CREATE TABLE `user` (
  `user_id` SMALLINT(6) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL UNIQUE,
  `password` VARCHAR(30) NOT NULL,
  `role` VARCHAR(30) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
