-- Fix for Attendance System Error
-- Run this in PHPMyAdmin on the live server

-- 1. Add salary_mode to users table (if not exists)
ALTER TABLE `users` ADD COLUMN `salary_mode` ENUM('tab', 'dab') NULL DEFAULT 'tab' AFTER `designation`;

-- 2. Add columns to attendances table (if not exists)
ALTER TABLE `attendances` ADD COLUMN `incentive_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `status`;
ALTER TABLE `attendances` ADD COLUMN `ta_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `incentive_amount`;
ALTER TABLE `attendances` ADD COLUMN `medicines_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `ta_amount`;
ALTER TABLE `attendances` ADD COLUMN `pathology_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `medicines_amount`;
ALTER TABLE `attendances` ADD COLUMN `membership_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `pathology_amount`;
ALTER TABLE `attendances` ADD COLUMN `ots_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `membership_amount`;
ALTER TABLE `attendances` ADD COLUMN `total_amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `ta_amount`;

-- 3. Create incentive_configs table (if not exists)
CREATE TABLE IF NOT EXISTS `incentive_configs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NULL,
  `designation` varchar(50) COLLATE utf8mb4_unicode_ci NULL,
  `incentive_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ta_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medicines_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pathology_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `membership_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ots_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `effective_from` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incentive_configs_user_id_foreign` (`user_id`),
  KEY `incentive_configs_designation_index` (`designation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
