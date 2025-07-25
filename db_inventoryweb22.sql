-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 14, 2025 at 01:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventoryweb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_08_19_000000_create_failed_jobs_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2022_10_31_061811_create_menu_table', 1),
(4, '2022_11_01_041110_create_table_role', 1),
(5, '2022_11_01_083314_create_table_user', 1),
(6, '2022_11_03_023905_create_table_submenu', 1),
(7, '2022_11_03_064417_create_tbl_akses', 1),
(8, '2022_11_08_024215_create_tbl_web', 1),
(9, '2022_11_15_131148_create_tbl_jenisbarang', 2),
(10, '2022_11_15_173700_create_tbl_satuan', 3),
(11, '2022_11_15_180434_create_tbl_merk', 4),
(12, '2022_11_16_120018_create_tbl_appreance', 5),
(13, '2022_11_25_141731_create_tbl_barang', 6),
(14, '2022_11_26_011349_create_tbl_customer', 7),
(16, '2022_11_28_151108_create_tbl_barangmasuk', 8),
(17, '2022_11_30_115904_create_tbl_barangkeluar', 9),
(19, '2024_03_15_create_tbl_stock_opname_requests_table', 10),
(20, '2025_05_12_083455_add_kolom_request_code', 11),
(21, '2025_05_13_034050_drop_column_product_id_stock_controls', 12);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akses`
--

CREATE TABLE `tbl_akses` (
  `akses_id` int(10) UNSIGNED NOT NULL,
  `menu_id` varchar(255) DEFAULT NULL,
  `submenu_id` varchar(255) DEFAULT NULL,
  `othermenu_id` varchar(255) DEFAULT NULL,
  `role_id` varchar(255) NOT NULL,
  `akses_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_akses`
--

INSERT INTO `tbl_akses` (`akses_id`, `menu_id`, `submenu_id`, `othermenu_id`, `role_id`, `akses_type`, `created_at`, `updated_at`) VALUES
(296, '1667444041', NULL, NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(297, '1667444041', NULL, NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(298, '1667444041', NULL, NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(299, '1667444041', NULL, NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(300, '1668509889', NULL, NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(301, '1668509889', NULL, NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(302, '1668509889', NULL, NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(303, '1668509889', NULL, NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(304, '1668510437', NULL, NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(305, '1668510437', NULL, NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(306, '1668510437', NULL, NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(307, '1668510437', NULL, NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(308, '1668510568', NULL, NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(309, '1668510568', NULL, NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(310, '1668510568', NULL, NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(311, '1668510568', NULL, NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(312, NULL, '9', NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(313, NULL, '9', NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(314, NULL, '9', NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(315, NULL, '9', NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(324, NULL, '10', NULL, '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(325, NULL, '10', NULL, '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(326, NULL, '10', NULL, '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(327, NULL, '10', NULL, '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(344, NULL, NULL, '1', '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(345, NULL, NULL, '2', '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(346, NULL, NULL, '3', '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(347, NULL, NULL, '4', '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(349, NULL, NULL, '6', '2', 'view', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(350, NULL, NULL, '1', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(351, NULL, NULL, '2', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(352, NULL, NULL, '3', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(353, NULL, NULL, '4', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(354, NULL, NULL, '5', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(355, NULL, NULL, '6', '2', 'create', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(356, NULL, NULL, '1', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(357, NULL, NULL, '2', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(358, NULL, NULL, '3', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(359, NULL, NULL, '4', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(360, NULL, NULL, '5', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(361, NULL, NULL, '6', '2', 'update', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(362, NULL, NULL, '1', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(363, NULL, NULL, '2', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(364, NULL, NULL, '3', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(365, NULL, NULL, '4', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(366, NULL, NULL, '5', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(367, NULL, NULL, '6', '2', 'delete', '2022-11-24 06:04:11', '2022-11-24 06:04:11'),
(368, '1667444041', NULL, NULL, '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(369, '1667444041', NULL, NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(370, '1667444041', NULL, NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(371, '1667444041', NULL, NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(372, '1668509889', NULL, NULL, '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(373, '1668509889', NULL, NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(374, '1668509889', NULL, NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(375, '1668509889', NULL, NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(376, '1668510437', NULL, NULL, '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(377, '1668510437', NULL, NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(378, '1668510437', NULL, NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(379, '1668510437', NULL, NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(381, '1668510568', NULL, NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(382, '1668510568', NULL, NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(383, '1668510568', NULL, NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(384, NULL, '9', NULL, '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(385, NULL, '9', NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(386, NULL, '9', NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(387, NULL, '9', NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(396, NULL, '10', NULL, '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(397, NULL, '10', NULL, '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(398, NULL, '10', NULL, '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(399, NULL, '10', NULL, '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(417, NULL, NULL, '2', '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(418, NULL, NULL, '3', '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(419, NULL, NULL, '4', '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(420, NULL, NULL, '5', '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(421, NULL, NULL, '6', '3', 'view', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(422, NULL, NULL, '1', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(423, NULL, NULL, '2', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(424, NULL, NULL, '3', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(425, NULL, NULL, '4', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(426, NULL, NULL, '5', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(427, NULL, NULL, '6', '3', 'create', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(428, NULL, NULL, '1', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(429, NULL, NULL, '2', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(430, NULL, NULL, '3', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(431, NULL, NULL, '4', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(432, NULL, NULL, '5', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(433, NULL, NULL, '6', '3', 'update', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(434, NULL, NULL, '1', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(435, NULL, NULL, '2', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(436, NULL, NULL, '3', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(437, NULL, NULL, '4', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(438, NULL, NULL, '5', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(439, NULL, NULL, '6', '3', 'delete', '2022-11-24 06:08:11', '2022-11-24 06:08:11'),
(464, NULL, '21', NULL, '2', 'view', '2022-11-30 05:58:28', '2022-11-30 05:58:28'),
(465, NULL, '22', NULL, '2', 'view', '2022-11-30 05:58:29', '2022-11-30 05:58:29'),
(466, NULL, '23', NULL, '2', 'view', '2022-11-30 05:58:31', '2022-11-30 05:58:31'),
(467, NULL, '21', NULL, '2', 'create', '2022-11-30 05:59:04', '2022-11-30 05:59:04'),
(468, NULL, '21', NULL, '2', 'update', '2022-11-30 05:59:05', '2022-11-30 05:59:05'),
(469, NULL, '21', NULL, '2', 'delete', '2022-11-30 05:59:06', '2022-11-30 05:59:06'),
(470, NULL, '22', NULL, '2', 'delete', '2022-11-30 05:59:07', '2022-11-30 05:59:07'),
(471, NULL, '22', NULL, '2', 'update', '2022-11-30 05:59:08', '2022-11-30 05:59:08'),
(472, NULL, '22', NULL, '2', 'create', '2022-11-30 05:59:09', '2022-11-30 05:59:09'),
(473, NULL, '23', NULL, '2', 'create', '2022-11-30 05:59:10', '2022-11-30 05:59:10'),
(474, NULL, '23', NULL, '2', 'update', '2022-11-30 05:59:11', '2022-11-30 05:59:11'),
(475, NULL, '23', NULL, '2', 'delete', '2022-11-30 05:59:12', '2022-11-30 05:59:12'),
(476, NULL, '21', NULL, '3', 'view', '2022-11-30 05:59:47', '2022-11-30 05:59:47'),
(477, NULL, '22', NULL, '3', 'view', '2022-11-30 05:59:48', '2022-11-30 05:59:48'),
(478, NULL, '23', NULL, '3', 'view', '2022-11-30 05:59:48', '2022-11-30 05:59:48'),
(479, NULL, '21', NULL, '3', 'create', '2022-11-30 06:00:24', '2022-11-30 06:00:24'),
(480, NULL, '21', NULL, '3', 'update', '2022-11-30 06:00:25', '2022-11-30 06:00:25'),
(481, NULL, '21', NULL, '3', 'delete', '2022-11-30 06:00:26', '2022-11-30 06:00:26'),
(482, NULL, '22', NULL, '3', 'delete', '2022-11-30 06:00:27', '2022-11-30 06:00:27'),
(483, NULL, '22', NULL, '3', 'update', '2022-11-30 06:00:28', '2022-11-30 06:00:28'),
(484, NULL, '22', NULL, '3', 'create', '2022-11-30 06:00:29', '2022-11-30 06:00:29'),
(485, NULL, '23', NULL, '3', 'create', '2022-11-30 06:00:30', '2022-11-30 06:00:30'),
(486, NULL, '23', NULL, '3', 'update', '2022-11-30 06:00:30', '2022-11-30 06:00:30'),
(487, NULL, '23', NULL, '3', 'delete', '2022-11-30 06:00:31', '2022-11-30 06:00:31'),
(488, '1667444041', NULL, NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(489, '1667444041', NULL, NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(490, '1667444041', NULL, NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(491, '1667444041', NULL, NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(493, '1668509889', NULL, NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(494, '1668509889', NULL, NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(495, '1668509889', NULL, NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(501, '1668510437', NULL, NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(502, '1668510437', NULL, NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(503, '1668510437', NULL, NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(504, '1668510568', NULL, NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(505, '1668510568', NULL, NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(506, '1668510568', NULL, NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(507, '1668510568', NULL, NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(508, NULL, '9', NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(509, NULL, '9', NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(510, NULL, '9', NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(511, NULL, '9', NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(516, NULL, '21', NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(517, NULL, '21', NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(518, NULL, '21', NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(519, NULL, '21', NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(520, NULL, '10', NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(521, NULL, '10', NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(522, NULL, '10', NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(523, NULL, '10', NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(528, NULL, '22', NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(529, NULL, '22', NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(530, NULL, '22', NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(531, NULL, '22', NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(536, NULL, '23', NULL, '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(537, NULL, '23', NULL, '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(538, NULL, '23', NULL, '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(539, NULL, '23', NULL, '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(545, NULL, NULL, '2', '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(546, NULL, NULL, '3', '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(547, NULL, NULL, '4', '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(548, NULL, NULL, '5', '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(549, NULL, NULL, '6', '4', 'view', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(550, NULL, NULL, '1', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(551, NULL, NULL, '2', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(552, NULL, NULL, '3', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(553, NULL, NULL, '4', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(554, NULL, NULL, '5', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(555, NULL, NULL, '6', '4', 'create', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(556, NULL, NULL, '1', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(557, NULL, NULL, '2', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(558, NULL, NULL, '3', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(559, NULL, NULL, '4', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(560, NULL, NULL, '5', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(561, NULL, NULL, '6', '4', 'update', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(562, NULL, NULL, '1', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(563, NULL, NULL, '2', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(564, NULL, NULL, '3', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(565, NULL, NULL, '4', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(566, NULL, NULL, '5', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(567, NULL, NULL, '6', '4', 'delete', '2022-12-06 02:34:31', '2022-12-06 02:34:31'),
(1307, '1688064226', NULL, NULL, '2', 'view', '2023-06-29 12:12:13', '2023-06-29 12:12:13'),
(1308, '1688064226', NULL, NULL, '2', 'create', '2023-06-29 12:12:16', '2023-06-29 12:12:16'),
(1309, '1688064226', NULL, NULL, '2', 'update', '2023-06-29 12:12:19', '2023-06-29 12:12:19'),
(1310, '1688064226', NULL, NULL, '2', 'delete', '2023-06-29 12:12:20', '2023-06-29 12:12:20'),
(1483, '1667444041', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1484, '1667444041', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1485, '1667444041', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1486, '1667444041', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1487, '1668509889', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1488, '1668509889', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1489, '1668509889', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1490, '1668509889', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1491, '1688064226', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1492, '1688064226', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1493, '1688064226', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1494, '1688064226', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1495, '1688066384', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1496, '1688066384', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1497, '1688066384', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1498, '1688066384', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1499, '1668510437', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1500, '1668510437', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1501, '1668510437', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1502, '1668510437', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1503, '1668510568', NULL, NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1504, '1668510568', NULL, NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1505, '1668510568', NULL, NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1506, '1668510568', NULL, NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1507, NULL, '9', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1508, NULL, '9', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1509, NULL, '9', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1510, NULL, '9', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1511, NULL, '21', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1512, NULL, '21', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1513, NULL, '21', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1514, NULL, '21', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1515, NULL, '30', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1516, NULL, '30', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1517, NULL, '30', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1518, NULL, '30', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1519, NULL, '10', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1520, NULL, '10', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1521, NULL, '10', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1522, NULL, '10', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1523, NULL, '22', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1524, NULL, '22', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1525, NULL, '22', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1526, NULL, '22', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1527, NULL, '31', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1528, NULL, '31', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1529, NULL, '31', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1530, NULL, '31', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1531, NULL, '23', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1532, NULL, '23', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1533, NULL, '23', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1534, NULL, '23', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1535, NULL, '32', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1536, NULL, '32', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1537, NULL, '32', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1538, NULL, '32', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1539, NULL, '33', NULL, '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1540, NULL, '33', NULL, '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1541, NULL, '33', NULL, '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1542, NULL, '33', NULL, '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1543, NULL, NULL, '1', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1544, NULL, NULL, '2', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1545, NULL, NULL, '3', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1546, NULL, NULL, '4', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1547, NULL, NULL, '5', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1548, NULL, NULL, '6', '1', 'view', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1549, NULL, NULL, '1', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1550, NULL, NULL, '2', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1551, NULL, NULL, '3', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1552, NULL, NULL, '4', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1553, NULL, NULL, '5', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1554, NULL, NULL, '6', '1', 'create', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1555, NULL, NULL, '1', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1556, NULL, NULL, '2', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1557, NULL, NULL, '3', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1558, NULL, NULL, '4', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1559, NULL, NULL, '5', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1560, NULL, NULL, '6', '1', 'update', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1561, NULL, NULL, '1', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1562, NULL, NULL, '2', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1563, NULL, NULL, '3', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1564, NULL, NULL, '4', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1565, NULL, NULL, '5', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1566, NULL, NULL, '6', '1', 'delete', '2023-06-29 12:20:21', '2023-06-29 12:20:21'),
(1567, '1746996525', NULL, NULL, '1', 'view', '2025-05-11 20:49:49', '2025-05-11 20:49:49'),
(1568, '1747026189', NULL, NULL, '3', 'view', '2025-05-12 05:04:43', '2025-05-12 05:04:43'),
(1569, '1747026189', NULL, NULL, '3', 'create', '2025-05-12 05:17:00', '2025-05-12 05:17:00'),
(1570, '1746996525', NULL, NULL, '1', 'create', '2025-05-13 11:13:11', '2025-05-13 11:13:11'),
(1571, '1746996525', NULL, NULL, '1', 'update', '2025-05-13 11:25:13', '2025-05-13 11:25:13'),
(1572, '1746996525', NULL, NULL, '1', 'view', '2025-05-13 11:25:24', '2025-05-13 11:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appreance`
--

CREATE TABLE `tbl_appreance` (
  `appreance_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `appreance_layout` varchar(255) DEFAULT NULL,
  `appreance_theme` varchar(255) DEFAULT NULL,
  `appreance_menu` varchar(255) DEFAULT NULL,
  `appreance_header` varchar(255) DEFAULT NULL,
  `appreance_sidestyle` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_appreance`
--

INSERT INTO `tbl_appreance` (`appreance_id`, `user_id`, `appreance_layout`, `appreance_theme`, `appreance_menu`, `appreance_header`, `appreance_sidestyle`, `created_at`, `updated_at`) VALUES
(2, '1', 'sidebar-mini', 'light-mode', 'dark-menu', 'color-header', 'default-menu', '2022-11-22 02:45:47', '2025-05-13 04:21:51'),
(3, '2', 'sidebar-mini', 'light-mode', 'dark-menu', 'header-light', 'sidenav-toggled', '2023-06-12 00:34:56', '2023-06-29 09:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `barang_id` int(11) NOT NULL,
  `jenisbarang_id` varchar(255) DEFAULT NULL,
  `satuan_id` varchar(255) DEFAULT NULL,
  `merk_id` varchar(255) DEFAULT NULL,
  `barang_kode` varchar(255) NOT NULL,
  `barang_nama` varchar(255) NOT NULL,
  `barang_slug` varchar(255) DEFAULT NULL,
  `barang_harga` varchar(255) NOT NULL,
  `barang_stok` varchar(255) NOT NULL,
  `barang_gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_barang`
--

INSERT INTO `tbl_barang` (`barang_id`, `jenisbarang_id`, `satuan_id`, `merk_id`, `barang_kode`, `barang_nama`, `barang_slug`, `barang_harga`, `barang_stok`, `barang_gambar`, `created_at`, `updated_at`) VALUES
(1, '1', '3', '1', 'BRG-1688072053663', 'Indomie', 'indomie', '124000', '20', 'MtzCnEqRsA3v05sePelKIaP95sFixrYHxafrTK1u.jpg', '2023-06-29 13:54:45', '2025-05-14 02:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barangkeluar`
--

CREATE TABLE `tbl_barangkeluar` (
  `bk_id` int(10) UNSIGNED NOT NULL,
  `bk_kode` varchar(255) NOT NULL,
  `barang_kode` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `bk_tanggal` varchar(255) NOT NULL,
  `bk_tujuan` varchar(255) DEFAULT NULL,
  `bk_jumlah` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_barangkeluar`
--

INSERT INTO `tbl_barangkeluar` (`bk_id`, `bk_kode`, `barang_kode`, `customer_id`, `bk_tanggal`, `bk_tujuan`, `bk_jumlah`, `created_at`, `updated_at`) VALUES
(1, 'BK-1688072948731', 'BRG-1688072053663', '1', '2023-06-28', 'Pasar Rambutan', '2', '2023-06-29 14:09:28', '2023-06-29 14:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barangmasuk`
--

CREATE TABLE `tbl_barangmasuk` (
  `bm_id` int(10) UNSIGNED NOT NULL,
  `bm_kode` varchar(255) NOT NULL,
  `barang_kode` varchar(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `bm_tanggal` varchar(255) NOT NULL,
  `bm_jumlah` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_barangmasuk`
--

INSERT INTO `tbl_barangmasuk` (`bm_id`, `bm_kode`, `barang_kode`, `supplier_id`, `bm_tanggal`, `bm_jumlah`, `created_at`, `updated_at`) VALUES
(1, 'BM-1688072349240', 'BRG-1688072053663', '1', '2023-06-27', '12', '2023-06-29 13:59:43', '2023-06-29 13:59:43'),
(2, 'BM-1747213936621', 'BRG-1688072053663', '1', '2025-05-20', '30', '2025-05-14 02:12:36', '2025-05-14 02:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_nama` varchar(255) NOT NULL,
  `customer_slug` varchar(255) NOT NULL,
  `customer_alamat` text DEFAULT NULL,
  `customer_notelp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customer_id`, `customer_nama`, `customer_slug`, `customer_alamat`, `customer_notelp`, `created_at`, `updated_at`) VALUES
(1, 'Rian Agus', 'rian-agus', 'Pasar Rambutan Jakarta', '0817654432122', '2023-06-29 14:01:12', '2023-06-29 14:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenisbarang`
--

CREATE TABLE `tbl_jenisbarang` (
  `jenisbarang_id` int(10) UNSIGNED NOT NULL,
  `jenisbarang_nama` varchar(255) NOT NULL,
  `jenisbarang_slug` varchar(255) NOT NULL,
  `jenisbarang_ket` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_jenisbarang`
--

INSERT INTO `tbl_jenisbarang` (`jenisbarang_id`, `jenisbarang_nama`, `jenisbarang_slug`, `jenisbarang_ket`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'makanan', NULL, '2023-06-29 13:26:20', '2023-06-29 13:26:20'),
(2, 'Pakaian', 'pakaian', NULL, '2023-06-29 13:26:33', '2023-06-29 13:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `menu_judul` varchar(255) NOT NULL,
  `menu_slug` varchar(255) NOT NULL,
  `menu_icon` varchar(255) NOT NULL,
  `menu_redirect` varchar(255) NOT NULL,
  `menu_sort` varchar(255) NOT NULL,
  `menu_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`menu_id`, `menu_judul`, `menu_slug`, `menu_icon`, `menu_redirect`, `menu_sort`, `menu_type`, `created_at`, `updated_at`) VALUES
(1667444041, 'Dashboard', 'dashboard', 'home', '/dashboard', '1', '1', '2022-11-15 03:51:04', '2023-06-29 12:18:54'),
(1668509889, 'Master Barang', 'master-barang', 'package', '-', '2', '2', '2022-11-15 03:58:09', '2023-06-29 12:18:54'),
(1668510437, 'Transaksi', 'transaksi', 'repeat', '-', '5', '2', '2022-11-15 04:07:17', '2023-06-29 12:19:55'),
(1668510568, 'Laporan', 'laporan', 'printer', '-', '6', '2', '2022-11-15 04:09:28', '2023-06-29 12:19:50'),
(1688064226, 'Supplier', 'supplier', 'user', '/supplier', '3', '1', '2023-06-29 11:43:46', '2023-06-29 12:18:54'),
(1688066384, 'Customer', 'customer', 'user', '/customer', '4', '1', '2023-06-29 12:19:44', '2023-06-29 12:19:55'),
(1746996525, 'StkOpname', 'stkopname', 'clipboard', '/stkopname', '7', '1', '2025-05-11 13:48:45', '2025-05-11 13:48:45'),
(1747026189, 'Request Opname', 'request-opname', 'file', '/picker', '8', '1', '2025-05-11 22:03:09', '2025-05-11 22:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_merk`
--

CREATE TABLE `tbl_merk` (
  `merk_id` int(10) UNSIGNED NOT NULL,
  `merk_nama` varchar(255) NOT NULL,
  `merk_slug` varchar(255) NOT NULL,
  `merk_keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_merk`
--

INSERT INTO `tbl_merk` (`merk_id`, `merk_nama`, `merk_slug`, `merk_keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Indofood', 'indofood', NULL, '2023-06-29 13:27:17', '2023-06-29 13:27:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_title` varchar(255) NOT NULL,
  `role_slug` varchar(255) NOT NULL,
  `role_desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`role_id`, `role_title`, `role_slug`, `role_desc`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super-admin', '-', '2022-11-15 03:51:04', '2022-11-15 03:51:04'),
(2, 'Admin', 'admin', '-', '2022-11-15 03:51:04', '2022-11-15 03:51:04'),
(3, 'Picker', 'picker', '-', '2022-11-15 03:51:04', '2022-11-15 03:51:04'),
(4, 'Head', 'head', NULL, '2022-12-06 02:33:27', '2022-12-06 02:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_satuan`
--

CREATE TABLE `tbl_satuan` (
  `satuan_id` int(10) UNSIGNED NOT NULL,
  `satuan_nama` varchar(255) NOT NULL,
  `satuan_slug` varchar(255) NOT NULL,
  `satuan_keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`satuan_id`, `satuan_nama`, `satuan_slug`, `satuan_keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Buah', 'buah', NULL, '2023-06-29 13:26:46', '2023-06-29 13:26:46'),
(2, 'Lusin', 'lusin', NULL, '2023-06-29 13:26:58', '2023-06-29 13:26:58'),
(3, 'Dus', 'dus', NULL, '2023-06-29 13:28:31', '2023-06-29 13:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_control`
--

CREATE TABLE `tbl_stock_control` (
  `stock_id` char(36) NOT NULL,
  `request_code` varchar(20) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `stock_in` decimal(10,2) DEFAULT NULL,
  `status_request` enum('pending','approve','reject') NOT NULL DEFAULT 'pending',
  `keterangan` varchar(500) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_stock_control`
--

INSERT INTO `tbl_stock_control` (`stock_id`, `request_code`, `request_date`, `stock_in`, `status_request`, `keterangan`, `user_id`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
('1ec1d19b-a447-4cb9-afff-f23eab40948e', 'SO-00000003', NULL, 0.00, 'approve', 'approve', 3, 1, '2025-05-14 01:53:45', '2025-05-13 04:15:57', '2025-05-14 01:53:45'),
('34fd1759-6433-498b-9127-635c32a7130c', 'SO-00000001', '2025-05-12', 0.00, 'reject', 'test tolak', 3, NULL, NULL, '2025-05-12 01:41:20', '2025-05-14 02:09:01'),
('7326b186-20cf-4b7a-8c8a-ec96511e26f5', 'SO-00000002', '2025-05-12', 0.00, 'reject', 'test', 3, NULL, NULL, '2025-05-12 03:52:28', '2025-05-14 02:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_control_details`
--

CREATE TABLE `tbl_stock_control_details` (
  `stock_detail_id` char(36) NOT NULL,
  `stock_id` char(36) NOT NULL,
  `barang_id` int(10) UNSIGNED NOT NULL COMMENT 'Referensi ke barang_id di tbl_barang',
  `stock_system` decimal(10,2) DEFAULT NULL,
  `stock_in` decimal(10,2) DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_stock_control_details`
--

INSERT INTO `tbl_stock_control_details` (`stock_detail_id`, `stock_id`, `barang_id`, `stock_system`, `stock_in`, `is_checked`, `created_at`, `updated_at`) VALUES
('d65b5aef-c0b3-47f2-b293-02db09fe7523', '1ec1d19b-a447-4cb9-afff-f23eab40948e', 1, 0.00, 20.00, 1, '2025-05-14 01:53:45', '2025-05-14 02:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_submenu`
--

CREATE TABLE `tbl_submenu` (
  `submenu_id` int(10) UNSIGNED NOT NULL,
  `menu_id` varchar(255) NOT NULL,
  `submenu_judul` varchar(255) NOT NULL,
  `submenu_slug` varchar(255) NOT NULL,
  `submenu_redirect` varchar(255) NOT NULL,
  `submenu_sort` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_submenu`
--

INSERT INTO `tbl_submenu` (`submenu_id`, `menu_id`, `submenu_judul`, `submenu_slug`, `submenu_redirect`, `submenu_sort`, `created_at`, `updated_at`) VALUES
(9, '1668510437', 'Barang Masuk', 'barang-masuk', '/barang-masuk', '1', '2022-11-15 04:08:19', '2022-11-15 04:08:19'),
(10, '1668510437', 'Barang Keluar', 'barang-keluar', '/barang-keluar', '2', '2022-11-15 04:08:19', '2022-11-15 04:08:19'),
(21, '1668510568', 'Lap Barang Masuk', 'lap-barang-masuk', '/lap-barang-masuk', '1', '2022-11-30 05:56:24', '2022-11-30 05:56:24'),
(22, '1668510568', 'Lap Barang Keluar', 'lap-barang-keluar', '/lap-barang-keluar', '2', '2022-11-30 05:56:24', '2022-11-30 05:56:24'),
(23, '1668510568', 'Lap Stok Barang', 'lap-stok-barang', '/lap-stok-barang', '3', '2022-11-30 05:56:24', '2022-11-30 05:56:24'),
(30, '1668509889', 'Jenis', 'jenis', '/jenisbarang', '1', '2023-06-29 12:15:29', '2023-06-29 12:15:29'),
(31, '1668509889', 'Satuan', 'satuan', '/satuan', '2', '2023-06-29 12:15:29', '2023-06-29 12:15:29'),
(32, '1668509889', 'Merk', 'merk', '/merk', '3', '2023-06-29 12:15:29', '2023-06-29 12:15:29'),
(33, '1668509889', 'Barang', 'barang', '/barang', '4', '2023-06-29 12:15:29', '2023-06-29 12:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_supplier`
--

CREATE TABLE `tbl_supplier` (
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `supplier_nama` varchar(255) NOT NULL,
  `supplier_slug` varchar(255) NOT NULL,
  `supplier_alamat` text DEFAULT NULL,
  `supplier_notelp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_supplier`
--

INSERT INTO `tbl_supplier` (`supplier_id`, `supplier_nama`, `supplier_slug`, `supplier_alamat`, `supplier_notelp`, `created_at`, `updated_at`) VALUES
(1, 'CV Intan Permata', 'cv-intan-permata', 'Jakarta', '0857654432111', '2023-06-29 12:13:09', '2023-06-29 12:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` varchar(255) NOT NULL,
  `user_nmlengkap` varchar(255) NOT NULL,
  `user_nama` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_foto` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `role_id`, `user_nmlengkap`, `user_nama`, `user_email`, `user_foto`, `user_password`, `created_at`, `updated_at`) VALUES
(1, '1', 'Super Administrator', 'superadmin', 'superadmin@gmail.com', '8znVp3RQ7kYI0JctHUWevTueUuu8B51qKXIiwm5l.png', '25d55ad283aa400af464c76d713c07ad', '2022-11-15 03:51:04', '2025-05-13 05:35:29'),
(2, '2', 'Administrator', 'admin', 'admin@gmail.com', 'undraw_profile.svg', '25d55ad283aa400af464c76d713c07ad', '2022-11-15 03:51:04', '2022-11-15 03:51:04'),
(3, '3', 'Kha huda', 'AccountPicker', 'Picker@gmail.com', 'undraw_profile.svg', '25d55ad283aa400af464c76d713c07ad', '2022-11-15 03:51:04', '2022-11-15 03:51:04'),
(4, '4', 'head ', 'head', 'head@gmail.com', 'undraw_profile.svg', '25d55ad283aa400af464c76d713c07ad', '2022-12-06 02:33:54', '2022-12-06 02:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_web`
--

CREATE TABLE `tbl_web` (
  `web_id` int(10) UNSIGNED NOT NULL,
  `web_nama` varchar(255) NOT NULL,
  `web_logo` varchar(255) NOT NULL,
  `web_deskripsi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_web`
--

INSERT INTO `tbl_web` (`web_id`, `web_nama`, `web_logo`, `web_deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Apps Smart', 'default.png', 'Mengelola Data Barang Masuk & Barang Keluar', '2022-11-15 03:51:04', '2025-05-12 02:40:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `tbl_akses`
--
ALTER TABLE `tbl_akses`
  ADD PRIMARY KEY (`akses_id`);

--
-- Indexes for table `tbl_appreance`
--
ALTER TABLE `tbl_appreance`
  ADD PRIMARY KEY (`appreance_id`);

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD PRIMARY KEY (`barang_id`,`barang_kode`,`barang_nama`,`barang_harga`,`barang_stok`);

--
-- Indexes for table `tbl_barangkeluar`
--
ALTER TABLE `tbl_barangkeluar`
  ADD PRIMARY KEY (`bk_id`);

--
-- Indexes for table `tbl_barangmasuk`
--
ALTER TABLE `tbl_barangmasuk`
  ADD PRIMARY KEY (`bm_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_jenisbarang`
--
ALTER TABLE `tbl_jenisbarang`
  ADD PRIMARY KEY (`jenisbarang_id`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `tbl_merk`
--
ALTER TABLE `tbl_merk`
  ADD PRIMARY KEY (`merk_id`);

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  ADD PRIMARY KEY (`satuan_id`);

--
-- Indexes for table `tbl_stock_control`
--
ALTER TABLE `tbl_stock_control`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `tbl_stock_control_details`
--
ALTER TABLE `tbl_stock_control_details`
  ADD KEY `tbl_stock_control_details_stock_id_foreign` (`stock_id`);

--
-- Indexes for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  ADD PRIMARY KEY (`submenu_id`);

--
-- Indexes for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_web`
--
ALTER TABLE `tbl_web`
  ADD PRIMARY KEY (`web_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_akses`
--
ALTER TABLE `tbl_akses`
  MODIFY `akses_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1573;

--
-- AUTO_INCREMENT for table `tbl_appreance`
--
ALTER TABLE `tbl_appreance`
  MODIFY `appreance_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  MODIFY `barang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_barangkeluar`
--
ALTER TABLE `tbl_barangkeluar`
  MODIFY `bk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_barangmasuk`
--
ALTER TABLE `tbl_barangmasuk`
  MODIFY `bm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_jenisbarang`
--
ALTER TABLE `tbl_jenisbarang`
  MODIFY `jenisbarang_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1747026190;

--
-- AUTO_INCREMENT for table `tbl_merk`
--
ALTER TABLE `tbl_merk`
  MODIFY `merk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  MODIFY `satuan_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  MODIFY `submenu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_supplier`
--
ALTER TABLE `tbl_supplier`
  MODIFY `supplier_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_web`
--
ALTER TABLE `tbl_web`
  MODIFY `web_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_stock_control_details`
--
ALTER TABLE `tbl_stock_control_details`
  ADD CONSTRAINT `tbl_stock_control_details_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `tbl_stock_control` (`stock_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
