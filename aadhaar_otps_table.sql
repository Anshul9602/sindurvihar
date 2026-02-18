-- SQL script to create aadhaar_otps table
-- This table stores Aadhaar OTP verification data for application forms

CREATE TABLE IF NOT EXISTS `aadhaar_otps` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `aadhaar_number` VARCHAR(12) NOT NULL,
  `otp` VARCHAR(10) DEFAULT NULL,
  `verified` TINYINT(1) DEFAULT 0,
  `api_response` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `aadhaar_number` (`aadhaar_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index for faster lookups
CREATE INDEX `idx_user_aadhaar_verified` ON `aadhaar_otps` (`user_id`, `aadhaar_number`, `verified`);

