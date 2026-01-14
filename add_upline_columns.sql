-- Add upline columns to users table for Office In-Charge functionality
-- Run this in phpMyAdmin or MySQL command line

USE hf_database;

-- Add upline_id column
ALTER TABLE `users` 
ADD COLUMN `upline_id` BIGINT UNSIGNED NULL AFTER `parent_id`;

-- Add upline_designation column
ALTER TABLE `users` 
ADD COLUMN `upline_designation` VARCHAR(255) NULL AFTER `upline_id`;

-- Add foreign key constraint for upline_id
ALTER TABLE `users` 
ADD CONSTRAINT `users_upline_id_foreign` 
FOREIGN KEY (`upline_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Verify the columns were added
DESCRIBE `users`;
