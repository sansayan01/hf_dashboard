-- SQL for creating and seeding role_permissions table
-- Use this if the automatic migration script fails

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_permission_key_unique` (`role`,`permission_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role`, `permission_key`, `is_enabled`, `created_at`, `updated_at`) VALUES
('office_in_charge', 'can_create_users', 1, NOW(), NOW()),
('office_in_charge', 'can_approve_users', 1, NOW(), NOW()),
('office_in_charge', 'can_view_downline', 1, NOW(), NOW()),
('office_in_charge', 'can_delete_users', 1, NOW(), NOW()),
('office_in_charge', 'can_create_surveys', 1, NOW(), NOW()),
('office_in_charge', 'can_view_reports', 1, NOW(), NOW()),
('office_in_charge', 'can_manage_appointments', 1, NOW(), NOW()),
('office_in_charge', 'can_edit_user_details', 1, NOW(), NOW()),

('hs', 'can_create_users', 1, NOW(), NOW()),
('hs', 'can_approve_users', 1, NOW(), NOW()),
('hs', 'can_view_downline', 1, NOW(), NOW()),
('hs', 'can_delete_users', 1, NOW(), NOW()),
('hs', 'can_create_surveys', 1, NOW(), NOW()),
('hs', 'can_view_reports', 1, NOW(), NOW()),
('hs', 'can_manage_appointments', 1, NOW(), NOW()),
('hs', 'can_edit_user_details', 1, NOW(), NOW()),

('dm', 'can_create_users', 1, NOW(), NOW()),
('dm', 'can_approve_users', 0, NOW(), NOW()),
('dm', 'can_view_downline', 1, NOW(), NOW()),
('dm', 'can_delete_users', 0, NOW(), NOW()),
('dm', 'can_create_surveys', 1, NOW(), NOW()),
('dm', 'can_view_reports', 1, NOW(), NOW()),
('dm', 'can_manage_appointments', 1, NOW(), NOW()),
('dm', 'can_edit_user_details', 1, NOW(), NOW()),

('bm', 'can_create_users', 1, NOW(), NOW()),
('bm', 'can_approve_users', 0, NOW(), NOW()),
('bm', 'can_view_downline', 1, NOW(), NOW()),
('bm', 'can_delete_users', 0, NOW(), NOW()),
('bm', 'can_create_surveys', 1, NOW(), NOW()),
('bm', 'can_view_reports', 0, NOW(), NOW()),
('bm', 'can_manage_appointments', 1, NOW(), NOW()),
('bm', 'can_edit_user_details', 0, NOW(), NOW()),

('rm', 'can_create_users', 1, NOW(), NOW()),
('rm', 'can_approve_users', 0, NOW(), NOW()),
('rm', 'can_view_downline', 1, NOW(), NOW()),
('rm', 'can_delete_users', 0, NOW(), NOW()),
('rm', 'can_create_surveys', 1, NOW(), NOW()),
('rm', 'can_view_reports', 0, NOW(), NOW()),
('rm', 'can_manage_appointments', 1, NOW(), NOW()),
('rm', 'can_edit_user_details', 0, NOW(), NOW()),

('ro', 'can_create_users', 0, NOW(), NOW()),
('ro', 'can_approve_users', 0, NOW(), NOW()),
('ro', 'can_view_downline', 0, NOW(), NOW()),
('ro', 'can_delete_users', 0, NOW(), NOW()),
('ro', 'can_create_surveys', 1, NOW(), NOW()),
('ro', 'can_view_reports', 0, NOW(), NOW()),
('ro', 'can_manage_appointments', 1, NOW(), NOW()),
('ro', 'can_edit_user_details', 0, NOW(), NOW());

COMMIT;
