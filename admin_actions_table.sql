-- Admin Actions Table
-- This table stores records of admin actions (reject/verify) on applications

CREATE TABLE IF NOT EXISTS `admin_actions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) UNSIGNED NOT NULL,
  `admin_id` INT(11) UNSIGNED DEFAULT NULL,
  `action_type` VARCHAR(20) NOT NULL COMMENT 'verified or rejected',
  `reason` TEXT DEFAULT NULL COMMENT 'Reason for rejection (if rejected)',
  `notes` TEXT DEFAULT NULL COMMENT 'Additional notes or comments',
  `confirmed` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Whether admin confirmed the action',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

