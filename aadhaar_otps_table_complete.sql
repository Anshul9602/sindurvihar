-- SQL script to create aadhaar_otps table with TruthScreen/UIDAI KYC fields
-- This table stores Aadhaar OTP verification data for application forms
-- Updated: Includes KYC data fields for government compliance

CREATE TABLE IF NOT EXISTS `aadhaar_otps` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `aadhaar_number` VARCHAR(12) NOT NULL,
  `otp` VARCHAR(10) DEFAULT NULL,
  `verified` TINYINT(1) DEFAULT 0,
  `api_response` TEXT DEFAULT NULL,
  `request_id` VARCHAR(100) DEFAULT NULL COMMENT 'TruthScreen API request ID',
  `aadhaar_last4` VARCHAR(4) DEFAULT NULL COMMENT 'Last 4 digits of Aadhaar (for compliance)',
  `kyc_name` VARCHAR(191) DEFAULT NULL COMMENT 'Name from Aadhaar KYC',
  `kyc_dob` VARCHAR(20) DEFAULT NULL COMMENT 'Date of Birth from Aadhaar KYC',
  `kyc_gender` VARCHAR(10) DEFAULT NULL COMMENT 'Gender from Aadhaar KYC',
  `kyc_address` TEXT DEFAULT NULL COMMENT 'Address from Aadhaar KYC',
  `kyc_pincode` VARCHAR(20) DEFAULT NULL COMMENT 'Pincode from Aadhaar KYC',
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `aadhaar_number` (`aadhaar_number`),
  KEY `request_id` (`request_id`),
  KEY `verified` (`verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index for faster lookups
CREATE INDEX `idx_user_aadhaar_verified` ON `aadhaar_otps` (`user_id`, `aadhaar_number`, `verified`);

-- Index for request_id lookups (TruthScreen API)
CREATE INDEX `idx_request_id` ON `aadhaar_otps` (`request_id`);

