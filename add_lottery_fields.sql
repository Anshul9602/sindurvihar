-- Add fields for lottery reservation system
-- These fields will be used to identify special reservation categories

ALTER TABLE `applications` 
ADD COLUMN `caste_category` VARCHAR(50) DEFAULT NULL COMMENT 'SC, ST, OBC, GENERAL' AFTER `income_category`,
ADD COLUMN `is_disabled` TINYINT(1) DEFAULT 0 COMMENT '5% reservation for disabled' AFTER `caste_category`,
ADD COLUMN `is_single_woman` TINYINT(1) DEFAULT 0 COMMENT '10% reservation for single woman/widow' AFTER `is_disabled`,
ADD COLUMN `is_transgender` TINYINT(1) DEFAULT 0 COMMENT 'Transgender reservation' AFTER `is_single_woman`,
ADD COLUMN `is_army` TINYINT(1) DEFAULT 0 COMMENT 'Army/Ex-serviceman reservation' AFTER `is_transgender`,
ADD COLUMN `is_media` TINYINT(1) DEFAULT 0 COMMENT 'Media reservation' AFTER `is_army`,
ADD COLUMN `is_govt_employee` TINYINT(1) DEFAULT 0 COMMENT 'Govt employee reservation' AFTER `is_media`;

-- Update existing records: Set caste_category from users.category if available
UPDATE `applications` a
INNER JOIN `users` u ON a.user_id = u.id
SET a.caste_category = u.category
WHERE a.caste_category IS NULL AND u.category IS NOT NULL;

-- Set service category mapping (income_category already exists)
-- EWS, LIG, MIG, Govt, Soldier are already in income_category

