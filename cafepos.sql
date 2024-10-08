-- Create Database
CREATE DATABASE IF NOT EXISTS cafepos;
USE cafepos;

-- Table structure for table `category`
CREATE TABLE `category` (
 `category_id` tinyint(4) NOT NULL AUTO_INCREMENT,
 `category_name` varchar(30) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `ingredients`
CREATE TABLE `ingredients` (
 `ingredient_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `ingredient_name` varchar(30) NOT NULL,
 `category_id` tinyint(4) NOT NULL,
 `quantity` float NOT NULL,
 `unit` varchar(5) NOT NULL,
 `total_price` float NOT NULL,
 `price_per_unit` float NOT NULL,
 `price_per_g_ml` float DEFAULT NULL,
 PRIMARY KEY (`ingredient_id`),
 UNIQUE KEY `item` (`ingredient_name`),
 KEY `category_id` (`category_id`),
 CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `orders`
CREATE TABLE `orders` (
 `order_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `user_id` smallint(6) NOT NULL,
 `total_amount` float NOT NULL,
 `tendered_amount` float NOT NULL,
 `change` float NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`order_id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `order_items`
CREATE TABLE `order_items` (
 `order_item_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `order_id` smallint(6) NOT NULL,
 `product_id` smallint(6) NOT NULL,
 `quantity` tinyint(4) NOT NULL,
 `subtotal` float NOT NULL,
 `profit` float NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`order_item_id`),
 KEY `order_id` (`order_id`),
 KEY `product_id` (`product_id`),
 CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
 CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `products`
CREATE TABLE `products` (
 `product_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `product_name` varchar(30) NOT NULL,
 `category_id` tinyint(4) NOT NULL,
 `product_image` blob NOT NULL,
 `cost_price` float NOT NULL,
 `selling_price` float NOT NULL,
 `profit` float NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`product_id`),
 KEY `category_id` (`category_id`),
 CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `product_ingredients`
CREATE TABLE `product_ingredients` (
 `product_id` smallint(6) NOT NULL,
 `ingredient_id` smallint(6) NOT NULL,
 `quantity_required` float NOT NULL,
 `price_per_g_ml` float NOT NULL,
 PRIMARY KEY (`product_id`,`ingredient_id`),
 KEY `ingredient_id` (`ingredient_id`),
 CONSTRAINT `product_ingredients_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
 CONSTRAINT `product_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `sales`
CREATE TABLE `sales` (
 `sale_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `order_id` smallint(6) NOT NULL,
 `total_amount` float NOT NULL,
 `proft` float NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`sale_id`),
 KEY `order_id` (`order_id`),
 CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Table structure for table `user`
CREATE TABLE `user` (
 `user_id` smallint(6) NOT NULL AUTO_INCREMENT,
 `username` varchar(30) NOT NULL,
 `password` varchar(30) NOT NULL,
 `role` varchar(30) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`user_id`),
 UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

COMMIT;
