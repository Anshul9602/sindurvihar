-- Plots Table
-- This table stores plot information for the housing scheme

CREATE TABLE IF NOT EXISTS `plots` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plot_name` VARCHAR(255) NOT NULL,
  `plot_number` VARCHAR(50) DEFAULT NULL,
  `category` VARCHAR(50) NOT NULL COMMENT 'EWS, LIG, MIG-A, MIG-B, HIG, General, etc.',
  `dimensions` VARCHAR(100) DEFAULT NULL COMMENT 'e.g., 30x40, 40x60, etc.',
  `area` DECIMAL(10,2) DEFAULT NULL COMMENT 'Area in square feet or square meters',
  `location` VARCHAR(255) NOT NULL,
  `plot_image` VARCHAR(255) DEFAULT NULL COMMENT 'Path to plot image',
  `quantity` INT(11) UNSIGNED NOT NULL DEFAULT 1,
  `available_quantity` INT(11) UNSIGNED NOT NULL DEFAULT 1,
  `price` DECIMAL(12,2) DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'available' COMMENT 'available, allocated, reserved',
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

