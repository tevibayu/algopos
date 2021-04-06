-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2020 at 03:31 AM
-- Server version: 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id_activity` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity` mediumtext,
  `module` tinytext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id_activity`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id_activity`, `user_id`, `activity`, `module`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 1, 'Create Cost id 2 ON 10.0.2.2', 'Cost', '2020-02-25 20:21:08', '2020-02-25 20:21:08', 0),
(2, 1, 'Create Cost id 3 ON 10.0.2.2', 'Cost', '2020-02-25 20:21:22', '2020-02-25 20:21:22', 0),
(3, 1, 'Delete Cost with id 3 ON 10.0.2.2', 'Cost', '2020-02-25 20:24:49', '2020-02-25 20:24:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assigned_roles`
--

CREATE TABLE IF NOT EXISTS `assigned_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assigned_roles_user_id_foreign` (`user_id`),
  KEY `assigned_roles_role_id_foreign` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `assigned_roles`
--

INSERT INTO `assigned_roles` (`id`, `user_id`, `role_id`) VALUES
(33, 1, 1),
(34, 2, 2),
(35, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cost`
--

CREATE TABLE IF NOT EXISTS `cost` (
  `id_cost` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL DEFAULT '0',
  `name` mediumtext,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cost`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cost`
--

INSERT INTO `cost` (`id_cost`, `id_type`, `name`, `is_active`) VALUES
(1, 1, 'BBM', 1),
(2, 1, 'Finance', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_menus`
--

CREATE TABLE IF NOT EXISTS `group_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) DEFAULT NULL COMMENT 'Back End, Front End',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `group_menus`
--

INSERT INTO `group_menus` (`id`, `group_name`) VALUES
(1, 'Back End'),
(2, 'Front End');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id_language` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `lang` varchar(150) NOT NULL,
  `name` varchar(100) NOT NULL,
  `flag` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_language`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id_language`, `code`, `lang`, `name`, `flag`) VALUES
(1, 'en', 'menus.language-picker.langs.en', 'English', 'public/img/english.png'),
(2, 'es', 'menus.language-picker.langs.es', 'Spanish', '15e9b726b6712154991b005fd3b3616a.png');

-- --------------------------------------------------------

--
-- Table structure for table `localizations`
--

CREATE TABLE IF NOT EXISTS `localizations` (
  `id_localization` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `timezone` varchar(50) NOT NULL,
  `modules_id_module` int(11) DEFAULT NULL,
  `records` varchar(255) NOT NULL,
  PRIMARY KEY (`id_localization`),
  KEY `modules_id_module` (`modules_id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `localizations`
--

INSERT INTO `localizations` (`id_localization`, `type`, `timezone`, `modules_id_module`, `records`) VALUES
(2, 'popular', '239', 1, '1'),
(3, 'except', '416', 1, '5');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT 'untitled link',
  `lang` varchar(100) NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `icon` varchar(45) DEFAULT 'fa fa-angle-right',
  `target` varchar(10) DEFAULT NULL COMMENT '0 = Internal\n1 = External',
  `group_menu` int(11) DEFAULT '1',
  `parent_id` int(11) DEFAULT '0',
  `permission_id` int(11) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1' COMMENT '0 = Inactive\n1 = Active',
  `order` int(11) DEFAULT NULL COMMENT 'the order of the menus',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `lang`, `link`, `icon`, `target`, `group_menu`, `parent_id`, `permission_id`, `status`, `order`) VALUES
(2, 'Settings', 'menus.title.settings', '#', 'fa fa-cogs', 'sametab', 1, 0, 2, 1, 6),
(8, 'Menu', 'menus.title.menu', 'admin/menu', 'fa fa-angle-right', 'sametab', 1, 2, 28, 1, 9),
(55, 'User Management', 'menus.title.user_management', 'admin/access', 'fa fa-angle-right', 'sametab', 1, 2, 2, 1, 7),
(56, 'General', 'menus.title.general', 'admin/settings/general', 'fa fa-angle-right', 'sametab', 1, 2, 32, 1, 2),
(57, 'Email', 'menus.title.email', 'admin/settings/email', 'fa fa-angle-right', 'sametab', 1, 2, 32, 0, 6),
(58, 'Language', 'menus.title.language', 'admin/language', 'fa fa-angle-right', 'sametab', 1, 2, 33, 0, 8),
(63, 'Master Data', '', '#', 'fa fa-file', 'sametab', 1, 0, 50, 1, 5),
(67, 'Cost', '', 'admin/cost', 'fa fa-angle-right', 'sametab', 1, 63, 50, 1, 1),
(71, 'All Reports', '', 'admin/transactionreport', 'fa fa-bar-chart-o', 'sametab', 1, 0, 53, 1, 4),
(75, 'Change Password', '', 'admin/password/change', 'fa fa-angle-right', 'sametab', 1, 2, 1, 0, 3),
(76, 'Profile Edit', '', 'admin/profile/edit', 'fa fa-angle-right', 'sametab', 1, 2, 1, 1, 5),
(77, 'Change Photo', '', 'admin/photo/change', 'fa fa-angle-right', 'sametab', 1, 2, 1, 1, 4),
(79, 'Fleet', '', 'admin/transaction/fleet', 'fa fa-cubes', 'sametab', 1, 0, 2, 1, 1),
(80, 'CLH', '', 'admin/transaction/clh', 'fa fa-cubes', 'sametab', 1, 0, 2, 1, 2),
(81, 'Operational', '', 'admin/transaction/operational', 'fa fa-cubes', 'sametab', 1, 0, 2, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2015_04_27_022849_create_user_providers_table', 1),
('2015_04_30_170442_setup_access_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(45) NOT NULL,
  `table_name` varchar(45) NOT NULL,
  `field_id` varchar(45) NOT NULL,
  `field_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id_module`, `module_name`, `table_name`, `field_id`, `field_name`) VALUES
(1, 'Tag Parents', 'tag_parents', 'id_tag_parent', 'tag_parent_name');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `group_id`, `name`, `display_name`, `system`, `sort`, `created_at`, `updated_at`) VALUES
(1, 6, 'view-backend', 'View Backend', 1, 1, '2015-10-20 19:21:26', '2015-10-27 20:53:46'),
(2, 1, 'view-access-management', 'View Access Management', 1, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(3, 2, 'create-users', 'Create Users', 1, 5, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(4, 2, 'edit-users', 'Edit Users', 1, 6, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(5, 2, 'delete-users', 'Delete Users', 1, 7, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(6, 2, 'change-user-password', 'Change User Password', 1, 8, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(7, 2, 'deactivate-users', 'Deactivate Users', 1, 9, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(8, 2, 'ban-users', 'Ban Users', 1, 10, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(9, 2, 'reactivate-users', 'Re-Activate Users', 1, 11, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(10, 2, 'unban-users', 'Un-Ban Users', 1, 12, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(11, 2, 'undelete-users', 'Restore Users', 1, 13, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(12, 2, 'permanently-delete-users', 'Permanently Delete Users', 1, 14, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(13, 2, 'resend-user-confirmation-email', 'Resend Confirmation E-mail', 1, 15, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(14, 3, 'create-roles', 'Create Roles', 1, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(15, 3, 'edit-roles', 'Edit Roles', 1, 3, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(16, 3, 'delete-roles', 'Delete Roles', 1, 4, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(17, 4, 'create-permission-groups', 'Create Permission Groups', 1, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(18, 4, 'edit-permission-groups', 'Edit Permission Groups', 1, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(19, 4, 'delete-permission-groups', 'Delete Permission Groups', 1, 3, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(20, 4, 'sort-permission-groups', 'Sort Permission Groups', 1, 4, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(21, 4, 'create-permissions', 'Create Permissions', 1, 5, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(22, 4, 'edit-permissions', 'Edit Permissions', 1, 6, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(23, 4, 'delete-permissions', 'Delete Permissions', 1, 7, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(24, 5, 'view-tag-parents', 'View Tag Parents', 0, 16, '2015-10-20 23:02:12', '2015-10-20 23:07:40'),
(25, 5, 'create-tag-parents', 'Create Tag Parents', 0, 0, '2015-10-20 23:03:47', '2015-11-03 20:32:31'),
(26, 5, 'edit-tag-parents', 'Edit Tag Parents', 0, 0, '2015-10-20 23:05:43', '2015-11-03 20:33:18'),
(27, 5, 'delete-tag-parents', 'Delete Tag Parents', 0, 0, '2015-10-20 23:06:20', '2015-11-03 20:32:57'),
(28, 7, 'view-menu', 'View Menu', 1, 0, '2015-10-27 23:08:58', '2015-10-29 00:50:51'),
(29, 7, 'create-menu', 'Create Menu', 1, 0, '2015-10-27 23:13:27', '2015-10-29 00:50:36'),
(30, 7, 'edit-menu', 'Edit Menu', 1, 0, '2015-10-27 23:13:46', '2015-10-29 00:50:46'),
(31, 7, 'delete-menu', 'Delete Menu', 1, 0, '2015-10-27 23:14:02', '2015-10-29 00:50:41'),
(32, 8, 'view-settings', 'View Settings', 1, 0, '2015-11-03 20:33:51', '2015-11-03 20:33:51'),
(33, 9, 'view-language', 'View Language', 1, 0, '2015-11-25 18:13:33', '2015-11-25 18:13:33'),
(34, 9, 'create-language', 'Create Language', 1, 0, '2015-11-25 18:16:02', '2015-11-25 18:16:02'),
(35, 9, 'edit-language', 'Edit Language', 1, 0, '2015-11-25 18:17:45', '2015-11-25 18:17:45'),
(36, 9, 'delete-language', 'Delete Language', 1, 0, '2015-11-25 18:18:41', '2015-11-25 18:18:41'),
(37, 10, 'view-localizations', 'View Localizations', 0, 0, '2015-12-01 01:24:20', '2015-12-01 01:24:20'),
(38, 11, 'view-except-localizations', 'View Except Localizations', 0, 0, '2015-12-01 01:25:05', '2015-12-01 01:25:05'),
(39, 11, 'create-except-localizations', 'Create Except Localizations', 0, 0, '2015-12-01 01:25:45', '2015-12-01 01:25:45'),
(40, 11, 'edit-except-localizations', 'Edit Except Localizations', 0, 0, '2015-12-01 01:26:22', '2015-12-01 01:26:22'),
(41, 11, 'delete-except-localizations', 'Delete Except Localizations', 0, 0, '2015-12-01 01:26:56', '2015-12-01 01:26:56'),
(42, 12, 'view-popular-localizations', 'View Popular Localizations', 0, 0, '2015-12-02 23:37:45', '2015-12-02 23:37:45'),
(43, 12, 'create-popular-localizations', 'Create Popular Localizations', 0, 0, '2015-12-02 23:38:12', '2015-12-02 23:38:12'),
(44, 12, 'edit-popular-localizations', 'Edit Popular Localizations', 0, 0, '2015-12-02 23:38:31', '2015-12-02 23:38:31'),
(45, 12, 'delete-popular-localizations', 'Delete Popular Localizations', 0, 0, '2015-12-02 23:38:46', '2015-12-02 23:38:46'),
(46, 13, 'view-feature-localizations', 'View Feature Localizations', 0, 0, '2015-12-03 23:22:28', '2015-12-03 23:22:28'),
(47, 13, 'create-feature-localizations', 'Create Feature Localizations', 0, 0, '2015-12-03 23:22:51', '2015-12-03 23:22:51'),
(48, 13, 'edit-feature-localizations', 'Edit Feature Localizations', 0, 0, '2015-12-03 23:23:07', '2015-12-03 23:23:07'),
(49, 13, 'delete-feature-localizations', 'Delete Feature Localizations', 0, 0, '2015-12-03 23:23:38', '2015-12-03 23:23:38'),
(50, 6, 'view-master', 'View Master Data', 0, 0, '2019-01-23 11:24:19', '2019-01-23 11:24:19'),
(52, 6, 'view-finance', 'View Finance', 0, 0, '2019-01-23 11:25:37', '2019-01-23 11:25:37'),
(53, 6, 'view-report', 'View All Reports', 0, 0, '2019-01-23 11:31:25', '2019-01-23 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `permission_dependencies`
--

CREATE TABLE IF NOT EXISTS `permission_dependencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `dependency_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `permission_dependencies_permission_id_foreign` (`permission_id`),
  KEY `permission_dependencies_dependency_id_foreign` (`dependency_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=109 ;

--
-- Dumping data for table `permission_dependencies`
--

INSERT INTO `permission_dependencies` (`id`, `permission_id`, `dependency_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(2, 3, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(3, 3, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(4, 4, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(5, 4, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(6, 5, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(7, 5, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(8, 6, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(9, 6, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(10, 7, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(11, 7, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(12, 8, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(13, 8, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(14, 9, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(15, 9, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(16, 10, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(17, 10, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(18, 11, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(19, 11, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(20, 12, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(21, 12, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(22, 13, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(23, 13, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(24, 14, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(25, 14, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(26, 15, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(27, 15, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(28, 16, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(29, 16, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(30, 17, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(31, 17, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(32, 18, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(33, 18, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(34, 19, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(35, 19, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(36, 20, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(37, 20, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(38, 21, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(39, 21, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(40, 22, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(41, 22, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(42, 23, 1, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(43, 23, 2, '2015-10-20 19:21:26', '2015-10-20 19:21:26'),
(46, 24, 1, '2015-10-20 23:07:40', '2015-10-20 23:07:40'),
(50, 29, 1, '2015-11-03 20:32:21', '2015-11-03 20:32:21'),
(51, 29, 28, '2015-11-03 20:32:21', '2015-11-03 20:32:21'),
(52, 25, 1, '2015-11-03 20:32:31', '2015-11-03 20:32:31'),
(53, 25, 24, '2015-11-03 20:32:31', '2015-11-03 20:32:31'),
(54, 31, 1, '2015-11-03 20:32:43', '2015-11-03 20:32:43'),
(55, 31, 28, '2015-11-03 20:32:43', '2015-11-03 20:32:43'),
(56, 27, 1, '2015-11-03 20:32:57', '2015-11-03 20:32:57'),
(57, 27, 24, '2015-11-03 20:32:57', '2015-11-03 20:32:57'),
(58, 30, 1, '2015-11-03 20:33:07', '2015-11-03 20:33:07'),
(59, 30, 28, '2015-11-03 20:33:07', '2015-11-03 20:33:07'),
(60, 26, 1, '2015-11-03 20:33:18', '2015-11-03 20:33:18'),
(61, 26, 24, '2015-11-03 20:33:18', '2015-11-03 20:33:18'),
(62, 28, 1, '2015-11-03 20:33:32', '2015-11-03 20:33:32'),
(63, 32, 1, '2015-11-03 20:33:51', '2015-11-03 20:33:51'),
(64, 33, 1, '2015-11-25 18:13:33', '2015-11-25 18:13:33'),
(65, 34, 1, '2015-11-25 18:16:02', '2015-11-25 18:16:02'),
(66, 34, 33, '2015-11-25 18:16:02', '2015-11-25 18:16:02'),
(67, 35, 1, '2015-11-25 18:17:45', '2015-11-25 18:17:45'),
(68, 35, 33, '2015-11-25 18:17:45', '2015-11-25 18:17:45'),
(69, 36, 1, '2015-11-25 18:18:41', '2015-11-25 18:18:41'),
(70, 36, 33, '2015-11-25 18:18:41', '2015-11-25 18:18:41'),
(71, 37, 1, '2015-12-01 01:24:20', '2015-12-01 01:24:20'),
(72, 38, 1, '2015-12-01 01:25:05', '2015-12-01 01:25:05'),
(73, 38, 37, '2015-12-01 01:25:05', '2015-12-01 01:25:05'),
(74, 39, 1, '2015-12-01 01:25:45', '2015-12-01 01:25:45'),
(75, 39, 38, '2015-12-01 01:25:45', '2015-12-01 01:25:45'),
(76, 39, 37, '2015-12-01 01:25:45', '2015-12-01 01:25:45'),
(77, 40, 1, '2015-12-01 01:26:22', '2015-12-01 01:26:22'),
(78, 40, 38, '2015-12-01 01:26:22', '2015-12-01 01:26:22'),
(79, 40, 37, '2015-12-01 01:26:22', '2015-12-01 01:26:22'),
(80, 41, 1, '2015-12-01 01:26:56', '2015-12-01 01:26:56'),
(81, 41, 38, '2015-12-01 01:26:56', '2015-12-01 01:26:56'),
(82, 41, 37, '2015-12-01 01:26:56', '2015-12-01 01:26:56'),
(83, 42, 1, '2015-12-02 23:37:45', '2015-12-02 23:37:45'),
(84, 42, 37, '2015-12-02 23:37:45', '2015-12-02 23:37:45'),
(85, 43, 1, '2015-12-02 23:38:12', '2015-12-02 23:38:12'),
(86, 43, 37, '2015-12-02 23:38:12', '2015-12-02 23:38:12'),
(87, 43, 42, '2015-12-02 23:38:12', '2015-12-02 23:38:12'),
(88, 44, 1, '2015-12-02 23:38:31', '2015-12-02 23:38:31'),
(89, 44, 37, '2015-12-02 23:38:31', '2015-12-02 23:38:31'),
(90, 44, 42, '2015-12-02 23:38:31', '2015-12-02 23:38:31'),
(91, 45, 1, '2015-12-02 23:38:46', '2015-12-02 23:38:46'),
(92, 45, 37, '2015-12-02 23:38:46', '2015-12-02 23:38:46'),
(93, 45, 42, '2015-12-02 23:38:46', '2015-12-02 23:38:46'),
(94, 46, 1, '2015-12-03 23:22:28', '2015-12-03 23:22:28'),
(95, 46, 37, '2015-12-03 23:22:28', '2015-12-03 23:22:28'),
(96, 47, 1, '2015-12-03 23:22:51', '2015-12-03 23:22:51'),
(97, 47, 46, '2015-12-03 23:22:51', '2015-12-03 23:22:51'),
(98, 47, 37, '2015-12-03 23:22:51', '2015-12-03 23:22:51'),
(99, 48, 1, '2015-12-03 23:23:07', '2015-12-03 23:23:07'),
(100, 48, 46, '2015-12-03 23:23:07', '2015-12-03 23:23:07'),
(101, 48, 37, '2015-12-03 23:23:07', '2015-12-03 23:23:07'),
(102, 49, 1, '2015-12-03 23:23:38', '2015-12-03 23:23:38'),
(103, 49, 46, '2015-12-03 23:23:38', '2015-12-03 23:23:38'),
(104, 49, 37, '2015-12-03 23:23:38', '2015-12-03 23:23:38'),
(105, 50, 1, '2019-01-23 11:24:19', '2019-01-23 11:24:19'),
(107, 52, 1, '2019-01-23 11:25:37', '2019-01-23 11:25:37'),
(108, 53, 1, '2019-01-23 11:31:25', '2019-01-23 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `permission_groups`
--

CREATE TABLE IF NOT EXISTS `permission_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `permission_groups`
--

INSERT INTO `permission_groups` (`id`, `parent_id`, `name`, `sort`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Access', 2, '2015-10-20 19:21:26', '2015-12-03 23:23:19'),
(2, 1, 'User', 1, '2015-10-20 19:21:26', '2015-12-01 01:22:12'),
(3, 1, 'Role', 2, '2015-10-20 19:21:26', '2015-12-01 01:22:12'),
(4, 1, 'Permission', 3, '2015-10-20 19:21:26', '2015-12-01 01:22:12'),
(5, NULL, 'Tag Parents', 4, '2015-10-20 21:55:43', '2015-12-03 23:23:19'),
(6, NULL, 'Backend', 1, '2015-10-27 20:53:29', '2015-12-03 23:23:19'),
(7, NULL, 'Menu', 7, '2015-10-27 23:08:20', '2015-12-03 23:23:19'),
(8, NULL, 'Settings', 5, '2015-11-03 20:33:38', '2015-12-03 23:23:19'),
(9, NULL, 'Language', 6, '2015-11-25 18:12:03', '2015-12-03 23:23:19'),
(10, NULL, 'Localizations', 3, '2015-12-01 01:21:32', '2015-12-03 23:23:19'),
(11, 10, 'Except Localizations', 4, '2015-12-01 01:21:51', '2015-12-01 01:22:12'),
(12, 10, 'Popular Localizations', 5, '2015-12-02 23:37:25', '2015-12-03 23:23:15'),
(13, 10, 'Feature Localizations', 6, '2015-12-03 23:22:11', '2015-12-03 23:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=224 ;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`) VALUES
(218, 1, 2),
(219, 50, 2),
(220, 53, 2),
(221, 53, 5),
(222, 52, 5),
(223, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE IF NOT EXISTS `permission_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_user_permission_id_foreign` (`permission_id`),
  KEY `permission_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `all` tinyint(1) NOT NULL DEFAULT '0',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `login_destination` varchar(255) COLLATE utf8_unicode_ci DEFAULT '/dashboard',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `all`, `sort`, `login_destination`, `created_at`, `updated_at`) VALUES
(1, 'Developer', 1, 1, '/admin/dashboard', '2015-10-20 19:21:26', '2020-02-13 09:18:03'),
(2, 'Admin', 0, 3, '/admin/dashboard', '2015-10-20 19:21:26', '2019-01-23 11:32:40'),
(5, 'Finance', 0, 0, '/admin/dashboard', '2019-01-23 11:32:01', '2019-01-23 11:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(100) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('access.users.change_email', '1'),
('access.users.default_per_page', '25'),
('app.debug', '1'),
('app.name', 'FinanSys'),
('app.profiler', '0'),
('mail.driver', 'smtp'),
('mail.from.address', 'ware.ms@gmail.com'),
('mail.from.name', 'WareMS'),
('mail.host', 'smtp.gmail.com'),
('mail.password', 'sinabung1'),
('mail.port', '587'),
('mail.username', 'WareMS No-Reply');

-- --------------------------------------------------------

--
-- Table structure for table `tag_parents`
--

CREATE TABLE IF NOT EXISTS `tag_parents` (
  `id_tag_parent` int(11) NOT NULL AUTO_INCREMENT,
  `tag_parent_name` varchar(45) DEFAULT NULL,
  `is_collection` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_tag_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tag_parents`
--

INSERT INTO `tag_parents` (`id_tag_parent`, `tag_parent_name`, `is_collection`, `is_active`) VALUES
(1, 'Seating', 1, 1),
(2, 'Tables & Desks', 0, 1),
(4, 'Galleria', 1, 1),
(5, 'Bedroom', 1, 1),
(6, 'Mirrors', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `driver_name` mediumtext NOT NULL,
  `drive_no` varchar(45) NOT NULL,
  `zone` varchar(45) NOT NULL,
  `amount` double NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `departed_at` date DEFAULT NULL,
  `returned_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id_transaction`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id_transaction`, `code`, `driver_name`, `drive_no`, `zone`, `amount`, `type`, `status`, `departed_at`, `returned_at`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 'CH-8901', 'Paijo', 'H 4567 UK', '1', 67000, 1, 1, '2020-02-26', '2020-03-06', '2020-02-15 09:00:00', '2020-02-25 19:29:28', 1),
(2, 'CH-5678', 'Franky', 'H 7890 YU', '2', 3009000, 1, 1, '2020-02-26', '2020-03-07', '2020-02-25 19:50:50', '2020-02-25 19:50:50', 0),
(3, 'CH-1235', 'Bramono', 'H 7656 UY', '1', 678888000, 1, 1, '2020-02-26', '2020-02-28', '2020-02-25 19:57:23', '2020-02-25 19:57:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_item`
--

CREATE TABLE IF NOT EXISTS `transaction_item` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaction` int(11) NOT NULL,
  `id_cost` int(11) NOT NULL,
  `desc` mediumtext NOT NULL,
  `amount` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `transaction_item`
--

INSERT INTO `transaction_item` (`id_item`, `id_transaction`, `id_cost`, `desc`, `amount`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 1, 'sdasdsad', 5600, '2020-02-13 07:00:00', '2020-02-13 07:00:00', 1),
(2, 1, 1, 'Test', 134, '2020-02-25 19:44:34', '2020-02-25 19:44:34', 0),
(3, 1, 1, 'rtyuuuu', 456, '2020-02-25 19:46:05', '2020-02-25 19:46:05', 0),
(4, 1, 1, 'ghyyyuuuuu', 56, '2020-02-25 19:47:03', '2020-02-25 19:47:03', 0),
(5, 1, 1, 'jhhhh', 78, '2020-02-25 19:49:16', '2020-02-25 19:49:16', 0),
(6, 3, 1, 'sdfdfsdf', 6789, '2020-02-25 19:57:58', '2020-02-25 19:57:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE IF NOT EXISTS `transaction_type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`id_type`, `name`, `is_active`) VALUES
(1, 'fleet', 1),
(2, 'clh', 1),
(3, 'operational', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `confirmation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `status`, `confirmation_code`, `confirmed`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `photo`) VALUES
(1, 'Developer', 'admin', 'admin@admin.com', '$2y$10$OY76tgro5do3PB2svVIXsOGc76Sz9.c5AsUb.Qo.Y2D2CPmOCFKxm', 1, 'd452eb69f3c772d15187903d4ac94d0a', 1, 'f3GvnjVcZAg6SHmpc0afeZPoYNjGDR2Kvn1zaSIp2k20DrGTz6MuCKZq6v4A', '2015-10-20 19:21:26', '2020-02-25 13:26:12', NULL, 'ebc4dca64f13a8db16d199633ce76b96.jpg'),
(2, 'Admin', 'user', 'admin@finansys.com', '$2y$10$acCF1JwNd9QW4J1pDbjl.ePEo1Zg0ZENMaVLnqaqaBcygB1JIwldq', 1, '1fb70f2975ef331dadbcd273fcbc0fcc', 1, 'xyD0qbVPGnT1MxoXjl50L9yoCIUVWzMFN20QRoZeLUKsTH5HjhDU06mxB8Te', '2015-10-20 19:21:26', '2020-02-13 09:16:34', NULL, '6d7fa58274ceaba5c15f0e518010c235.png'),
(4, 'Finance', 'finance', 'finance@finansys.com', '$2y$10$kkJtIFT7ntjcmNUftLTuheJqqZ028ip5e63dZw8kTWmlbxvdwLhxi', 1, 'c3b0ae71532fad4c0d502d83216ce1dc', 1, 'x3Bd0es6xHIvalT49lEKBdMTHuFmnM3AbcRd23liio7Rq3jXrjtxVuZPZRYG', '2019-01-23 11:35:29', '2020-02-13 09:17:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_providers`
--

CREATE TABLE IF NOT EXISTS `user_providers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_providers_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_roles`
--
ALTER TABLE `assigned_roles`
  ADD CONSTRAINT `assigned_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `assigned_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `localizations`
--
ALTER TABLE `localizations`
  ADD CONSTRAINT `localizations_ibfk_1` FOREIGN KEY (`modules_id_module`) REFERENCES `modules` (`id_module`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_dependencies`
--
ALTER TABLE `permission_dependencies`
  ADD CONSTRAINT `permission_dependencies_dependency_id_foreign` FOREIGN KEY (`dependency_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `permission_dependencies_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_providers`
--
ALTER TABLE `user_providers`
  ADD CONSTRAINT `user_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
