-- SQL script to add KYC fields to existing aadhaar_otps table
-- Run this if you already have the table created and need to add the new fields

-- Add TruthScreen/UIDAI KYC compliance fields
ALTER TABLE `aadhaar_otps`
  ADD COLUMN `request_id` VARCHAR(100) DEFAULT NULL COMMENT 'TruthScreen API request ID' AFTER `api_response`,
  ADD COLUMN `aadhaar_last4` VARCHAR(4) DEFAULT NULL COMMENT 'Last 4 digits of Aadhaar (for compliance)' AFTER `request_id`,
  ADD COLUMN `kyc_name` VARCHAR(191) DEFAULT NULL COMMENT 'Name from Aadhaar KYC' AFTER `aadhaar_last4`,
  ADD COLUMN `kyc_dob` VARCHAR(20) DEFAULT NULL COMMENT 'Date of Birth from Aadhaar KYC' AFTER `kyc_name`,
  ADD COLUMN `kyc_gender` VARCHAR(10) DEFAULT NULL COMMENT 'Gender from Aadhaar KYC' AFTER `kyc_dob`,
  ADD COLUMN `kyc_address` TEXT DEFAULT NULL COMMENT 'Address from Aadhaar KYC' AFTER `kyc_gender`,
  ADD COLUMN `kyc_pincode` VARCHAR(20) DEFAULT NULL COMMENT 'Pincode from Aadhaar KYC' AFTER `kyc_address`;

-- Add index for request_id lookups
CREATE INDEX `idx_request_id` ON `aadhaar_otps` (`request_id`);

-- Add index for verified status
CREATE INDEX `idx_verified` ON `aadhaar_otps` (`verified`);

