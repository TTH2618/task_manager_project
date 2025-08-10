-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management_db`
--
CREATE DATABASE IF NOT EXISTS `task_management_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `task_management_db`;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `create_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `description`, `create_at`) VALUES
(1, 'Phòng IT', '', '2025-07-25'),
(49, 'Phòng kinh tế', 'abc123', '2025-07-28');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipient` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `task_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `recipient`, `type`, `task_id`, `project_id`, `date`, `is_read`) VALUES
(1, 'Bạn được thêm vào là thành viên của dự án \'test\'. Bấm vào đây để xem chi tiết.', 3, 'Dự án mới', 0, 2, '2025-07-25 12:21:19', 0),
(2, 'Công việc \'new task\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 1, 0, '2025-07-25 12:29:18', 0),
(3, 'Đã có cập nhật tiến độ công việc \'new task\' mà bạn đã giao. Bấm vào đây để xem chi tiết.', 1, 'Cập nhật tiến độ', 1, 0, '2025-07-25 12:30:43', 1),
(4, 'Công việc \'beta\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 2, 0, '2025-07-25 12:59:26', 0),
(5, 'Công việc \'beta\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 4, 'Công việc mới', 2, 0, '2025-07-25 12:59:26', 0),
(6, 'Đã có cập nhật tiến độ công việc \'beta\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 0, 'Cập nhật tiến độ', 2, 0, '2025-07-25 12:59:43', 0),
(7, 'Công việc \'test\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 3, 0, '2025-07-25 13:02:42', 0),
(8, 'Công việc \'test\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 4, 'Công việc mới', 3, 0, '2025-07-25 13:02:42', 0),
(9, 'Đã có cập nhật tiến độ công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 0, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:02:52', 0),
(11, 'Đã có cập nhật tiến độ công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:06:25', 0),
(12, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:12:04', 0),
(13, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:18:45', 0),
(14, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:23:01', 0),
(15, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 13:23:01', 0),
(16, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:05:45', 0),
(17, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:05:45', 0),
(18, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:07:23', 0),
(19, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:07:23', 0),
(20, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:08:43', 0),
(21, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:08:43', 0),
(22, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:34:24', 0),
(23, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 20:34:24', 0),
(24, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 3, 0, '2025-07-25 21:50:12', 0),
(25, 'Quản lý đã phản hồi công việc \'test\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 4, 'Cập nhật tiến độ', 3, 0, '2025-07-25 21:50:12', 0),
(26, 'Bạn được thêm vào là thành viên của dự án \'test2 up\'. Bấm vào đây để xem chi tiết.', 3, 'Dự án mới', 0, 3, '2025-07-25 22:30:52', 0),
(27, 'Bạn được thêm vào là quản lý của dự án \'test2 up\'. Bấm vào đây để xem chi tiết.', 2, 'Dự án mới', 0, 3, '2025-07-25 22:30:52', 0),
(28, 'Công việc \'beta\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 4, 0, '2025-07-26 17:26:56', 0),
(29, 'Công việc \'test123\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 5, 0, '2025-07-26 17:28:33', 0),
(30, 'Công việc \'beta\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 4, 0, '2025-07-27 10:15:42', 1),
(31, 'Công việc \'coi\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 6, 0, '2025-07-28 10:48:22', 0),
(32, 'Công việc \'thử\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 7, 0, '2025-07-28 10:58:21', 0),
(33, 'Công việc \'thử\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 8, 0, '2025-08-02 09:46:56', 1),
(34, 'Công việc \'test123\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 5, 0, '2025-08-02 10:10:27', 1),
(35, 'Đã có cập nhật tiến độ công việc \'thử\' mà bạn đã giao. Bấm vào đây để xem chi tiết.', 2, 'Cập nhật tiến độ', 7, 0, '2025-08-02 10:11:31', 0),
(36, 'Công việc \'thử\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 2, 'Công việc hoàn thành', 7, 0, '2025-08-02 10:11:40', 1),
(37, 'Quản lý đã phản hồi công việc \'thử\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 7, 0, '2025-08-02 10:14:39', 1),
(38, 'Quản lý đã phản hồi công việc \'thử\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 2, 'Cập nhật tiến độ', 8, 0, '2025-08-04 16:27:33', 0),
(39, 'Đã có cập nhật tiến độ công việc \'thử\' mà bạn đã giao. Bấm vào đây để xem chi tiết.', 2, 'Cập nhật tiến độ', 7, 0, '2025-08-04 16:36:22', 0),
(40, 'Công việc \'date test\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 9, 0, '2025-08-05 12:38:48', 0),
(41, 'Công việc \'date test2\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 4, 'Công việc mới', 10, 0, '2025-08-05 12:57:09', 0),
(42, 'Công việc \'test\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 3, 0, '2025-08-06 13:35:44', 1),
(43, 'Đã có cập nhật tiến độ công việc \'test\' mà bạn đã giao. Bấm vào đây để xem chi tiết.', 1, 'Cập nhật tiến độ', 3, 0, '2025-08-06 14:34:04', 1),
(44, 'Công việc \'test\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 3, 0, '2025-08-06 14:34:55', 1),
(45, 'Công việc \'thử\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 2, 'Công việc hoàn thành', 7, 0, '2025-08-06 14:41:31', 0),
(46, 'Công việc \'thử\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 2, 'Công việc hoàn thành', 7, 0, '2025-08-06 14:43:25', 0),
(47, 'Công việc \'beta\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 4, 0, '2025-08-06 14:55:36', 1),
(48, 'Công việc \'test\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 3, 0, '2025-08-06 14:58:32', 1),
(49, 'Công việc \'test\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 4, 'Công việc chưa hoàn thành', 3, 0, '2025-08-06 14:58:32', 0),
(50, 'Công việc \'test\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 3, 0, '2025-08-06 15:03:11', 1),
(51, 'Công việc \'test\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 3, 0, '2025-08-06 15:03:41', 0),
(52, 'Công việc \'test\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 4, 'Công việc chưa hoàn thành', 3, 0, '2025-08-06 15:03:41', 0),
(53, 'Công việc \'amcamkas\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 11, 0, '2025-08-06 15:28:01', 0),
(54, 'Công việc \'ádasdasdad\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 12, 0, '2025-08-06 16:17:31', 0),
(55, 'Công việc \'ádasdasdad\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 13, 0, '2025-08-06 16:18:01', 0),
(56, 'Công việc \'làm xong báo cáo\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 14, 0, '2025-08-06 16:28:37', 0),
(57, 'Công việc \'beta\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 15, 0, '2025-08-06 16:41:03', 0),
(58, 'Công việc \'date8\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 4, 'Công việc mới', 16, 0, '2025-08-06 16:48:00', 0),
(59, 'Công việc \'passt\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 2, 'Công việc mới', 17, 0, '2025-08-06 16:56:06', 0),
(60, 'Công việc \'passt\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 17, 0, '2025-08-06 17:06:46', 0),
(61, 'Công việc \'passt\' mà bạn giao đã được hoàn thành. Bấm vào đây để kiểm tra.', 1, 'Công việc hoàn thành', 17, 0, '2025-08-06 17:10:10', 0),
(62, 'Công việc \'passt\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 2, 'Công việc chưa hoàn thành', 17, 0, '2025-08-06 17:10:51', 0),
(63, 'Công việc \'pọect\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 18, 0, '2025-08-06 17:14:20', 0),
(64, 'Công việc \'last\' đã được giao cho bạn. Bấm vào đây để xem chi tiết.', 3, 'Công việc mới', 19, 0, '2025-08-07 12:05:47', 0),
(65, 'Công việc \'last\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 19, 0, '2025-08-07 12:05:59', 0),
(66, 'Quản lý đã phản hồi công việc \'beta\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 15, 0, '2025-08-07 14:34:16', 0),
(67, 'Quản lý đã phản hồi công việc \'beta\' mà bạn tham gia. Bấm vào đây để xem chi tiết.', 3, 'Cập nhật tiến độ', 15, 0, '2025-08-07 14:34:32', 1),
(68, 'Công việc \'beta\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 15, 0, '2025-08-07 14:38:35', 0),
(69, 'Công việc \'beta\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 15, 0, '2025-08-07 14:38:37', 0),
(70, 'Công việc \'beta\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 15, 0, '2025-08-07 14:45:33', 0),
(71, 'Công việc \'beta\' được đánh giá chưa hoàn thành. Bấm vào đây để xem kiểm tra và hoàn thành lại công việc.', 3, 'Công việc chưa hoàn thành', 15, 0, '2025-08-07 14:45:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress`
--

INSERT INTO `progress` (`id`, `task_id`, `user_id`, `comment`, `file`, `created_date`) VALUES
(1, 1, 1, 'abc', '', '2025-07-25 12:30:43'),
(2, 2, 1, 'abc', '', '2025-07-25 12:59:43'),
(3, 3, 1, 'abc', '', '2025-07-25 13:02:52'),
(4, 3, 3, 'abc2', '1753423557_unnamed.png', '2025-07-25 13:05:57'),
(5, 3, 1, 'dfg', '', '2025-07-25 13:06:25'),
(6, 3, 1, 'zxc', '', '2025-07-25 13:12:04'),
(7, 3, 1, 'zvb', '', '2025-07-25 13:18:45'),
(8, 3, 1, 'hjk', '', '2025-07-25 13:23:01'),
(9, 3, 1, 'tét', '', '2025-07-25 20:05:45'),
(10, 3, 1, 'tét2', '', '2025-07-25 20:07:23'),
(11, 3, 1, 'tet3', '', '2025-07-25 20:08:43'),
(12, 3, 1, 't4', '', '2025-07-25 20:34:24'),
(13, 3, 1, 't5', '', '2025-07-25 21:50:12'),
(14, 7, 3, 'abc', '1754104291_unnamed.png', '2025-08-02 10:11:31'),
(15, 7, 2, 'lamf chuwa xog \r\nlamf laij', '', '2025-08-02 10:14:39'),
(16, 8, 1, 'avc', '', '2025-08-04 16:27:33'),
(17, 7, 1, 'aaaaaaaaaaaaa', '', '2025-08-04 16:36:22'),
(18, 3, 4, 'asd', '1754465644_e5070e19-07c8-4299-b78d-951d2be5d653_width=1066&height=1600.jpg', '2025-08-06 14:34:04'),
(19, 15, 1, 'có tiến độ mới', '', '2025-08-07 14:34:16'),
(20, 15, 1, 'có file công việc', '1754552072_unnamed.png', '2025-08-07 14:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `manager_id` int(11) NOT NULL,
  `employee_id` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(11) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `manager_id`, `employee_id`, `department_id`, `start_date`, `end_date`, `status`, `create_at`) VALUES
(2, 'test', 'Tuyệt vời! Mình thấy file Excel mẫu của bạn — bạn muốn xuất dữ liệu báo cáo dự án từ MySQL ra file Excel bằng PHP, đúng không?\r\nMình sẽ hướng dẫn một ví dụ đầy đủ, bạn chỉ cần copy về chạy.', 2, '3', 1, '2025-07-25', '2025-08-10', 1, '2025-07-25 12:18:20');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `employee_id` text DEFAULT NULL,
  `end_date` date NOT NULL,
  `status` int(11) DEFAULT NULL,
  `start_date` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `project_id`, `created_by`, `description`, `employee_id`, `end_date`, `status`, `start_date`, `created_at`) VALUES
(14, 'làm xong báo cáo', 0, 1, '', '2', '2025-08-07', 1, '2025-08-06', '2025-08-06 09:28:37'),
(15, 'beta', 0, 1, 'ẫdasxzc', '3', '2025-08-06', 1, '2025-08-06', '2025-08-06 09:41:03'),
(16, 'date8', 0, 1, '', '4', '2025-08-08', 1, '2025-08-06', '2025-08-06 09:48:00'),
(17, 'passt', 0, 1, '', '2', '2025-08-04', 2, '2025-08-06', '2025-08-06 09:56:06'),
(18, 'pọect', 2, 1, '', '3', '2025-08-08', 1, '2025-08-06', '2025-08-06 10:14:20'),
(19, 'last', 2, 1, 'done', '3', '2025-08-07', 2, '2025-08-07', '2025-08-07 05:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','employee') NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `birthday`, `password`, `role`, `avatar`, `department_id`, `created_at`) VALUES
(1, 'administrator', 'admin', 'admin@admin.com', '2025-07-24', '$2y$10$4mHDXomcg2pn3bAmYY.8auGTiZSdflbiB0e3kpaESwuDfm99f9q2S', 'admin', 'img/user.jpg', 0, '2025-07-25 05:00:48'),
(2, 'Từ Thế Hiện', 'tth', 'tuthehien2618@gmail.com', '2025-07-25', '$2y$10$mmGUtuEDnjZNfW9yINAKVehGcJGn.UWB0Nrs3qFy/Jgzmp8K38Yo.', 'manager', 'img/avatar_6883120388076.jpg', 1, '2025-07-25 05:11:31'),
(3, 'Hien Thế', 'htt', 'htt@g.com', '2025-07-22', '$2y$10$E2VAqVrnPJLwlB.iuhIcPeLl/CM0KREMh4.lIbBTxuE21JUCadyIa', 'employee', 'img/user.jpg', 1, '2025-07-25 05:14:53'),
(4, 'testpic2', 'testpic', 'tespic@gmail.com', '2025-07-24', '$2y$10$A0wccUx50nFE.LJnO1fjzeCVsCQ7nAj8mPV.vtaB.UIuMKJpvYDwK', 'employee', 'img/user.jpg', 0, '2025-07-25 05:50:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`employee_id`(768));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
