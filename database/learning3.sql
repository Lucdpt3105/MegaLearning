-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 07, 2025 lúc 11:21 AM
-- Phiên bản máy phục vụ: 9.4.0
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `learning3`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `user_id` int NOT NULL,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`user_id`, `user_name`, `user_email`, `user_password`, `user_role`, `created_at`, `status`) VALUES
(1, 'Admin', 'admin@example.com', 'password_admin_hash', 'admin', '2025-11-07 17:20:36', 'active'),
(2, 'Teacher A', 'teacher@example.com', 'password_teacher_hash', 'teacher', '2025-11-07 17:20:36', 'active'),
(3, 'Student 1', 'student1@example.com', 'password_student_hash', 'student', '2025-11-07 17:20:36', 'active'),
(4, 'Student 2', 'student2@example.com', 'password_student_hash', 'student', '2025-11-07 17:20:36', 'active');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ai_proctoring_sessions`
--

CREATE TABLE `ai_proctoring_sessions` (
  `proctor_id` int NOT NULL,
  `user_exam_id` int DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `flags` json DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int NOT NULL,
  `announcement_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `announcement_content` text COLLATE utf8mb4_unicode_ci,
  `announcement_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answers`
--

CREATE TABLE `answers` (
  `answer_id` int NOT NULL,
  `answer_text` text COLLATE utf8mb4_unicode_ci,
  `answer_question_id` int DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `answers`
--

INSERT INTO `answers` (`answer_id`, `answer_text`, `answer_question_id`, `is_correct`) VALUES
(1, 'went', 1, 1),
(2, 'goed', 1, 0),
(3, 'gone', 1, 0),
(4, 'goes', 1, 0),
(5, 'an', 2, 1),
(6, 'a', 2, 0),
(7, 'the', 2, 0),
(8, 'no article', 2, 0),
(9, 'mysqli_connect', 3, 1),
(10, 'mysql_connect', 3, 0),
(11, 'pdo_connect', 3, 0),
(12, 'connect_db', 3, 0),
(13, 'Representational State Transfer', 4, 1),
(14, 'Remote Execution Service Tool', 4, 0),
(15, 'Random Easy Service Term', 4, 0),
(16, 'None of the above', 4, 0),
(17, 'at', 5, 1),
(18, 'in', 5, 0),
(19, 'on', 5, 0),
(20, 'to', 5, 0),
(21, 'ol', 6, 1),
(22, 'ul', 6, 0),
(23, 'li', 6, 0),
(24, 'list', 6, 0),
(25, 'Option 1 for Q1', 7, 1),
(26, 'Option 2 for Q1', 7, 0),
(27, 'Option 3 for Q1', 7, 0),
(28, 'Option 4 for Q1', 7, 0),
(29, 'Option 1 for Q2', 8, 1),
(30, 'Option 2 for Q2', 8, 0),
(31, 'Option 3 for Q2', 8, 0),
(32, 'Option 4 for Q2', 8, 0),
(33, 'Option 1 for Q3', 9, 1),
(34, 'Option 2 for Q3', 9, 0),
(35, 'Option 3 for Q3', 9, 0),
(36, 'Option 4 for Q3', 9, 0),
(37, 'Option 1 for Q4', 10, 1),
(38, 'Option 2 for Q4', 10, 0),
(39, 'Option 3 for Q4', 10, 0),
(40, 'Option 4 for Q4', 10, 0),
(41, 'Option 1 for Q5', 11, 1),
(42, 'Option 2 for Q5', 11, 0),
(43, 'Option 3 for Q5', 11, 0),
(44, 'Option 4 for Q5', 11, 0),
(45, 'Option 1 for Q6', 12, 1),
(46, 'Option 2 for Q6', 12, 0),
(47, 'Option 3 for Q6', 12, 0),
(48, 'Option 4 for Q6', 12, 0),
(49, 'Option 1 for Q7', 13, 1),
(50, 'Option 2 for Q7', 13, 0),
(51, 'Option 3 for Q7', 13, 0),
(52, 'Option 4 for Q7', 13, 0),
(53, 'Option 1 for Q8', 14, 1),
(54, 'Option 2 for Q8', 14, 0),
(55, 'Option 3 for Q8', 14, 0),
(56, 'Option 4 for Q8', 14, 0),
(57, 'Option 1 for Q9', 15, 1),
(58, 'Option 2 for Q9', 15, 0),
(59, 'Option 3 for Q9', 15, 0),
(60, 'Option 4 for Q9', 15, 0),
(61, 'Option 1 for Q10', 16, 1),
(62, 'Option 2 for Q10', 16, 0),
(63, 'Option 3 for Q10', 16, 0),
(64, 'Option 4 for Q10', 16, 0),
(65, 'Option 1 for Q11', 17, 1),
(66, 'Option 2 for Q11', 17, 0),
(67, 'Option 3 for Q11', 17, 0),
(68, 'Option 4 for Q11', 17, 0),
(69, 'Option 1 for Q12', 18, 1),
(70, 'Option 2 for Q12', 18, 0),
(71, 'Option 3 for Q12', 18, 0),
(72, 'Option 4 for Q12', 18, 0),
(73, 'Option 1 for Q13', 19, 1),
(74, 'Option 2 for Q13', 19, 0),
(75, 'Option 3 for Q13', 19, 0),
(76, 'Option 4 for Q13', 19, 0),
(77, 'Option 1 for Q14', 20, 1),
(78, 'Option 2 for Q14', 20, 0),
(79, 'Option 3 for Q14', 20, 0),
(80, 'Option 4 for Q14', 20, 0),
(81, 'Option 1 for Q15', 21, 1),
(82, 'Option 2 for Q15', 21, 0),
(83, 'Option 3 for Q15', 21, 0),
(84, 'Option 4 for Q15', 21, 0),
(85, 'Option 1 for Q16', 22, 1),
(86, 'Option 2 for Q16', 22, 0),
(87, 'Option 3 for Q16', 22, 0),
(88, 'Option 4 for Q16', 22, 0),
(89, 'Option 1 for Q17', 23, 1),
(90, 'Option 2 for Q17', 23, 0),
(91, 'Option 3 for Q17', 23, 0),
(92, 'Option 4 for Q17', 23, 0),
(93, 'Option 1 for Q18', 24, 1),
(94, 'Option 2 for Q18', 24, 0),
(95, 'Option 3 for Q18', 24, 0),
(96, 'Option 4 for Q18', 24, 0),
(97, 'Option 1 for Q19', 25, 1),
(98, 'Option 2 for Q19', 25, 0),
(99, 'Option 3 for Q19', 25, 0),
(100, 'Option 4 for Q19', 25, 0),
(101, 'Option 1 for Q20', 26, 1),
(102, 'Option 2 for Q20', 26, 0),
(103, 'Option 3 for Q20', 26, 0),
(104, 'Option 4 for Q20', 26, 0),
(105, 'Option 1 for Q21', 27, 1),
(106, 'Option 2 for Q21', 27, 0),
(107, 'Option 3 for Q21', 27, 0),
(108, 'Option 4 for Q21', 27, 0),
(109, 'Option 1 for Q22', 28, 1),
(110, 'Option 2 for Q22', 28, 0),
(111, 'Option 3 for Q22', 28, 0),
(112, 'Option 4 for Q22', 28, 0),
(113, 'Option 1 for Q23', 29, 1),
(114, 'Option 2 for Q23', 29, 0),
(115, 'Option 3 for Q23', 29, 0),
(116, 'Option 4 for Q23', 29, 0),
(117, 'Option 1 for Q24', 30, 1),
(118, 'Option 2 for Q24', 30, 0),
(119, 'Option 3 for Q24', 30, 0),
(120, 'Option 4 for Q24', 30, 0),
(121, 'Option 1 for Q25', 31, 1),
(122, 'Option 2 for Q25', 31, 0),
(123, 'Option 3 for Q25', 31, 0),
(124, 'Option 4 for Q25', 31, 0),
(125, 'Option 1 for Q26', 32, 1),
(126, 'Option 2 for Q26', 32, 0),
(127, 'Option 3 for Q26', 32, 0),
(128, 'Option 4 for Q26', 32, 0),
(129, 'Option 1 for Q27', 33, 1),
(130, 'Option 2 for Q27', 33, 0),
(131, 'Option 3 for Q27', 33, 0),
(132, 'Option 4 for Q27', 33, 0),
(133, 'Option 1 for Q28', 34, 1),
(134, 'Option 2 for Q28', 34, 0),
(135, 'Option 3 for Q28', 34, 0),
(136, 'Option 4 for Q28', 34, 0),
(137, 'Option 1 for Q29', 35, 1),
(138, 'Option 2 for Q29', 35, 0),
(139, 'Option 3 for Q29', 35, 0),
(140, 'Option 4 for Q29', 35, 0),
(141, 'Option 1 for Q30', 36, 1),
(142, 'Option 2 for Q30', 36, 0),
(143, 'Option 3 for Q30', 36, 0),
(144, 'Option 4 for Q30', 36, 0),
(145, 'Option 1 for Q31', 37, 1),
(146, 'Option 2 for Q31', 37, 0),
(147, 'Option 3 for Q31', 37, 0),
(148, 'Option 4 for Q31', 37, 0),
(149, 'Option 1 for Q32', 38, 1),
(150, 'Option 2 for Q32', 38, 0),
(151, 'Option 3 for Q32', 38, 0),
(152, 'Option 4 for Q32', 38, 0),
(153, 'Option 1 for Q33', 39, 1),
(154, 'Option 2 for Q33', 39, 0),
(155, 'Option 3 for Q33', 39, 0),
(156, 'Option 4 for Q33', 39, 0),
(157, 'Option 1 for Q34', 40, 1),
(158, 'Option 2 for Q34', 40, 0),
(159, 'Option 3 for Q34', 40, 0),
(160, 'Option 4 for Q34', 40, 0),
(161, 'Option 1 for Q35', 41, 1),
(162, 'Option 2 for Q35', 41, 0),
(163, 'Option 3 for Q35', 41, 0),
(164, 'Option 4 for Q35', 41, 0),
(165, 'Option 1 for Q36', 42, 1),
(166, 'Option 2 for Q36', 42, 0),
(167, 'Option 3 for Q36', 42, 0),
(168, 'Option 4 for Q36', 42, 0),
(169, 'Option 1 for Q37', 43, 1),
(170, 'Option 2 for Q37', 43, 0),
(171, 'Option 3 for Q37', 43, 0),
(172, 'Option 4 for Q37', 43, 0),
(173, 'Option 1 for Q38', 44, 1),
(174, 'Option 2 for Q38', 44, 0),
(175, 'Option 3 for Q38', 44, 0),
(176, 'Option 4 for Q38', 44, 0),
(177, 'Option 1 for Q39', 45, 1),
(178, 'Option 2 for Q39', 45, 0),
(179, 'Option 3 for Q39', 45, 0),
(180, 'Option 4 for Q39', 45, 0),
(181, 'Option 1 for Q40', 46, 1),
(182, 'Option 2 for Q40', 46, 0),
(183, 'Option 3 for Q40', 46, 0),
(184, 'Option 4 for Q40', 46, 0),
(185, 'Option 1 for Q41', 47, 1),
(186, 'Option 2 for Q41', 47, 0),
(187, 'Option 3 for Q41', 47, 0),
(188, 'Option 4 for Q41', 47, 0),
(189, 'Option 1 for Q42', 48, 1),
(190, 'Option 2 for Q42', 48, 0),
(191, 'Option 3 for Q42', 48, 0),
(192, 'Option 4 for Q42', 48, 0),
(193, 'Option 1 for Q43', 49, 1),
(194, 'Option 2 for Q43', 49, 0),
(195, 'Option 3 for Q43', 49, 0),
(196, 'Option 4 for Q43', 49, 0),
(197, 'Option 1 for Q44', 50, 1),
(198, 'Option 2 for Q44', 50, 0),
(199, 'Option 3 for Q44', 50, 0),
(200, 'Option 4 for Q44', 50, 0),
(201, 'Option 1 for Q45', 51, 1),
(202, 'Option 2 for Q45', 51, 0),
(203, 'Option 3 for Q45', 51, 0),
(204, 'Option 4 for Q45', 51, 0),
(205, 'Option 1 for Q46', 52, 1),
(206, 'Option 2 for Q46', 52, 0),
(207, 'Option 3 for Q46', 52, 0),
(208, 'Option 4 for Q46', 52, 0),
(209, 'Option 1 for Q47', 53, 1),
(210, 'Option 2 for Q47', 53, 0),
(211, 'Option 3 for Q47', 53, 0),
(212, 'Option 4 for Q47', 53, 0),
(213, 'Option 1 for Q48', 54, 1),
(214, 'Option 2 for Q48', 54, 0),
(215, 'Option 3 for Q48', 54, 0),
(216, 'Option 4 for Q48', 54, 0),
(217, 'Option 1 for Q49', 55, 1),
(218, 'Option 2 for Q49', 55, 0),
(219, 'Option 3 for Q49', 55, 0),
(220, 'Option 4 for Q49', 55, 0),
(221, 'Option 1 for Q50', 56, 1),
(222, 'Option 2 for Q50', 56, 0),
(223, 'Option 3 for Q50', 56, 0),
(224, 'Option 4 for Q50', 56, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attachments`
--

CREATE TABLE `attachments` (
  `attachment_id` int NOT NULL,
  `owner_table` varchar(100) DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_url` varchar(512) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `badges`
--

CREATE TABLE `badges` (
  `badge_id` int NOT NULL,
  `badge_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge_description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int NOT NULL,
  `room_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `message_text` text,
  `sent_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_rooms`
--

CREATE TABLE `chat_rooms` (
  `room_id` int NOT NULL,
  `room_name` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_rooms`
--

INSERT INTO `chat_rooms` (`room_id`, `room_name`, `created_at`) VALUES
(1, 'Lớp Tiếng Anh - Lý thuyết', '2025-11-07 17:20:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dailychallenges`
--

CREATE TABLE `dailychallenges` (
  `daily_challenge_id` int NOT NULL,
  `daily_challenge_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `daily_challenge_description` text COLLATE utf8mb4_unicode_ci,
  `daily_challenge_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `examquestions`
--

CREATE TABLE `examquestions` (
  `exam_question_id` int NOT NULL,
  `exam_question_exam_id` int DEFAULT NULL,
  `exam_question_question_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `examquestions`
--

INSERT INTO `examquestions` (`exam_question_id`, `exam_question_exam_id`, `exam_question_question_id`) VALUES
(1, 1, 2),
(2, 1, 1),
(3, 1, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `examresults`
--

CREATE TABLE `examresults` (
  `exam_result_id` int NOT NULL,
  `exam_result_user_exam_id` int DEFAULT NULL,
  `exam_result_question_id` int DEFAULT NULL,
  `exam_result_selected_answer_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `examresults`
--

INSERT INTO `examresults` (`exam_result_id`, `exam_result_user_exam_id`, `exam_result_question_id`, `exam_result_selected_answer_id`) VALUES
(1, 1, 2, 5),
(2, 1, 1, 1),
(3, 1, 5, 17);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exams`
--

CREATE TABLE `exams` (
  `exam_id` int NOT NULL,
  `exam_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_subject_id` int DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `exam_duration` int DEFAULT NULL,
  `total_marks` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `exams`
--

INSERT INTO `exams` (`exam_id`, `exam_title`, `exam_subject_id`, `exam_date`, `exam_duration`, `total_marks`) VALUES
(1, 'Midterm - Tiếng Anh', 1, '2025-11-07', 60, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int NOT NULL,
  `feedback_user_id` int DEFAULT NULL,
  `feedback_content` text COLLATE utf8mb4_unicode_ci,
  `feedback_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `forumanswers`
--

CREATE TABLE `forumanswers` (
  `forum_answer_id` int NOT NULL,
  `forum_question_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `answer_content` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `forumquestions`
--

CREATE TABLE `forumquestions` (
  `forum_question_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `leaderboard`
--

CREATE TABLE `leaderboard` (
  `leaderboard_id` int NOT NULL,
  `leaderboard_user_id` int DEFAULT NULL,
  `leaderboard_score` int DEFAULT NULL,
  `leaderboard_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `meetingparticipants`
--

CREATE TABLE `meetingparticipants` (
  `participant_id` int NOT NULL,
  `meeting_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `joined_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `meetings`
--

CREATE TABLE `meetings` (
  `meeting_id` int NOT NULL,
  `host_user_id` int DEFAULT NULL,
  `topic` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `meeting_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '0001_01_01_000000_create_users_table', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notes`
--

CREATE TABLE `notes` (
  `note_id` int NOT NULL,
  `note_user_id` int DEFAULT NULL,
  `note_content` text COLLATE utf8mb4_unicode_ci,
  `note_create_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `payment_user_id` int DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `practiceresults`
--

CREATE TABLE `practiceresults` (
  `practice_result_id` int NOT NULL,
  `practice_result_user_practice_id` int DEFAULT NULL,
  `practice_result_question_id` int DEFAULT NULL,
  `practice_result_selected_answer_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `practicetestquestions`
--

CREATE TABLE `practicetestquestions` (
  `practice_test_question_id` int NOT NULL,
  `practice_test_question_test_id` int DEFAULT NULL,
  `practice_test_question_question_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `practicetests`
--

CREATE TABLE `practicetests` (
  `practice_test_id` int NOT NULL,
  `practice_test_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `practice_test_subject_id` int DEFAULT NULL,
  `practice_test_duration` int DEFAULT NULL,
  `total_marks` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `practicetests`
--

INSERT INTO `practicetests` (`practice_test_id`, `practice_test_title`, `practice_test_subject_id`, `practice_test_duration`, `total_marks`) VALUES
(1, 'Practice - Ngữ pháp cơ bản', 1, 30, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `progresstracker`
--

CREATE TABLE `progresstracker` (
  `progress_tracker_id` int NOT NULL,
  `progress_tracker_user_id` int DEFAULT NULL,
  `progress_tracker_topic_id` int DEFAULT NULL,
  `progress_tracker_progress` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `question_id` int NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci,
  `question_topic_id` int DEFAULT NULL,
  `question_type_id` int DEFAULT NULL,
  `question_difficulty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `questions`
--

INSERT INTO `questions` (`question_id`, `question_text`, `question_topic_id`, `question_type_id`, `question_difficulty`) VALUES
(1, 'What is the past tense of \"go\"?', 2, 1, 1),
(2, 'Choose the correct article: ____ apple a day keeps the doctor away.', 1, 1, 1),
(3, 'Which PHP function is used to connect to MySQLi?', 3, 1, 2),
(4, 'What does REST stand for?', 4, 1, 2),
(5, 'Select the correct preposition: She arrived ____ the airport at 6.', 2, 1, 1),
(6, 'Which tag is used for an ordered list in HTML?', NULL, 1, 1),
(7, 'Sample Question 1: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(8, 'Sample Question 2: This is a PHP & MySQL question.', 3, 1, 2),
(9, 'Sample Question 3: This is a PHP & MySQL question.', 3, 1, 3),
(10, 'Sample Question 4: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(11, 'Sample Question 5: This is a REST API question.', 4, 1, 3),
(12, 'Sample Question 6: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(13, 'Sample Question 7: This is a REST API question.', 4, 1, 1),
(14, 'Sample Question 8: This is a REST API question.', 4, 1, 2),
(15, 'Sample Question 9: This is a REST API question.', 4, 1, 1),
(16, 'Sample Question 10: This is a PHP & MySQL question.', 3, 1, 2),
(17, 'Sample Question 11: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(18, 'Sample Question 12: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(19, 'Sample Question 13: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(20, 'Sample Question 14: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(21, 'Sample Question 15: This is a PHP & MySQL question.', 3, 1, 1),
(22, 'Sample Question 16: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(23, 'Sample Question 17: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(24, 'Sample Question 18: This is a PHP & MySQL question.', 3, 1, 1),
(25, 'Sample Question 19: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(26, 'Sample Question 20: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(27, 'Sample Question 21: This is a REST API question.', 4, 1, 3),
(28, 'Sample Question 22: This is a PHP & MySQL question.', 3, 1, 3),
(29, 'Sample Question 23: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(30, 'Sample Question 24: This is a PHP & MySQL question.', 3, 1, 2),
(31, 'Sample Question 25: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(32, 'Sample Question 26: This is a PHP & MySQL question.', 3, 1, 1),
(33, 'Sample Question 27: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(34, 'Sample Question 28: This is a Từ vựng giao tiếp question.', 2, 1, 1),
(35, 'Sample Question 29: This is a REST API question.', 4, 1, 2),
(36, 'Sample Question 30: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(37, 'Sample Question 31: This is a PHP & MySQL question.', 3, 1, 1),
(38, 'Sample Question 32: This is a Ngữ pháp cơ bản question.', 1, 1, 2),
(39, 'Sample Question 33: This is a Từ vựng giao tiếp question.', 2, 1, 3),
(40, 'Sample Question 34: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(41, 'Sample Question 35: This is a REST API question.', 4, 1, 3),
(42, 'Sample Question 36: This is a Từ vựng giao tiếp question.', 2, 1, 2),
(43, 'Sample Question 37: This is a Từ vựng giao tiếp question.', 2, 1, 3),
(44, 'Sample Question 38: This is a Ngữ pháp cơ bản question.', 1, 1, 2),
(45, 'Sample Question 39: This is a Ngữ pháp cơ bản question.', 1, 1, 3),
(46, 'Sample Question 40: This is a PHP & MySQL question.', 3, 1, 2),
(47, 'Sample Question 41: This is a PHP & MySQL question.', 3, 1, 2),
(48, 'Sample Question 42: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(49, 'Sample Question 43: This is a REST API question.', 4, 1, 1),
(50, 'Sample Question 44: This is a PHP & MySQL question.', 3, 1, 1),
(51, 'Sample Question 45: This is a REST API question.', 4, 1, 3),
(52, 'Sample Question 46: This is a PHP & MySQL question.', 3, 1, 3),
(53, 'Sample Question 47: This is a Ngữ pháp cơ bản question.', 1, 1, 1),
(54, 'Sample Question 48: This is a Ngữ pháp cơ bản question.', 1, 1, 2),
(55, 'Sample Question 49: This is a Từ vựng giao tiếp question.', 2, 1, 1),
(56, 'Sample Question 50: This is a Từ vựng giao tiếp question.', 2, 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questiontypes`
--

CREATE TABLE `questiontypes` (
  `question_type_id` int NOT NULL,
  `question_type_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `questiontypes`
--

INSERT INTO `questiontypes` (`question_type_id`, `question_type_name`) VALUES
(1, 'Multiple Choice');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `role_id` int NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'teacher', 'Giảng viên'),
(3, 'student', 'Sinh viên');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('O4nmAAiiWarlCjdIfmXLMetTI3HYqigU8FK0ej5C', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0hrOUlUbVNja0wxR2ZXblpHMlpkOXpsNnE3VFo5akhTeVRaNnpsTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762509070);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `studymaterials`
--

CREATE TABLE `studymaterials` (
  `study_material_id` int NOT NULL,
  `study_material_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `study_material_content` text COLLATE utf8mb4_unicode_ci,
  `study_material_topic_id` int DEFAULT NULL,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `studymaterials`
--

INSERT INTO `studymaterials` (`study_material_id`, `study_material_title`, `study_material_content`, `study_material_topic_id`, `file_url`, `file_type`) VALUES
(1, 'Ngữ pháp cơ bản - PDF', 'Tài liệu ngữ pháp cơ bản', 1, NULL, 'pdf'),
(2, 'Giới thiệu PHP', 'Slides giới thiệu PHP', 3, NULL, 'pptx');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `studysessions`
--

CREATE TABLE `studysessions` (
  `study_session_id` int NOT NULL,
  `study_session_user_id` int DEFAULT NULL,
  `study_session_start_time` datetime DEFAULT NULL,
  `study_session_end_time` datetime DEFAULT NULL,
  `study_session_topic_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subjectcategories`
--

CREATE TABLE `subjectcategories` (
  `subject_category_id` int NOT NULL,
  `subject_category_subject_id` int DEFAULT NULL,
  `subject_category_category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int NOT NULL,
  `subject_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`) VALUES
(1, 'Tiếng Anh'),
(2, 'Lập Trình Web');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int NOT NULL,
  `subscription_user_id` int DEFAULT NULL,
  `subscription_start_date` date DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL,
  `subscription_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topics`
--

CREATE TABLE `topics` (
  `topic_id` int NOT NULL,
  `topic_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topic_subject_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topics`
--

INSERT INTO `topics` (`topic_id`, `topic_name`, `topic_subject_id`) VALUES
(1, 'Ngữ pháp cơ bản', 1),
(2, 'Từ vựng giao tiếp', 1),
(3, 'PHP & MySQL', 2),
(4, 'REST API', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `userbadges`
--

CREATE TABLE `userbadges` (
  `user_badge_id` int NOT NULL,
  `user_badge_user_id` int DEFAULT NULL,
  `user_badge_badge_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `userchallenges`
--

CREATE TABLE `userchallenges` (
  `user_challenge_id` int NOT NULL,
  `user_challenge_user_id` int DEFAULT NULL,
  `user_challenge_challenge_id` int DEFAULT NULL,
  `user_challenge_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `userexams`
--

CREATE TABLE `userexams` (
  `user_exam_id` int NOT NULL,
  `user_exam_user_id` int DEFAULT NULL,
  `user_exam_exam_id` int DEFAULT NULL,
  `user_exam_score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `userexams`
--

INSERT INTO `userexams` (`user_exam_id`, `user_exam_user_id`, `user_exam_exam_id`, `user_exam_score`) VALUES
(1, 3, 1, 78);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `userpractices`
--

CREATE TABLE `userpractices` (
  `user_practice_id` int NOT NULL,
  `user_practice_user_id` int DEFAULT NULL,
  `user_practice_test_id` int DEFAULT NULL,
  `user_practice_score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `userpractices`
--

INSERT INTO `userpractices` (`user_practice_id`, `user_practice_user_id`, `user_practice_test_id`, `user_practice_score`) VALUES
(1, 3, 1, 85);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`user_id`);

--
-- Chỉ mục cho bảng `ai_proctoring_sessions`
--
ALTER TABLE `ai_proctoring_sessions`
  ADD PRIMARY KEY (`proctor_id`),
  ADD KEY `user_exam_id` (`user_exam_id`);

--
-- Chỉ mục cho bảng `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Chỉ mục cho bảng `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `answer_question_id` (`answer_question_id`);

--
-- Chỉ mục cho bảng `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachment_id`);

--
-- Chỉ mục cho bảng `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Chỉ mục cho bảng `dailychallenges`
--
ALTER TABLE `dailychallenges`
  ADD PRIMARY KEY (`daily_challenge_id`);

--
-- Chỉ mục cho bảng `examquestions`
--
ALTER TABLE `examquestions`
  ADD PRIMARY KEY (`exam_question_id`),
  ADD KEY `exam_question_exam_id` (`exam_question_exam_id`),
  ADD KEY `exam_question_question_id` (`exam_question_question_id`);

--
-- Chỉ mục cho bảng `examresults`
--
ALTER TABLE `examresults`
  ADD PRIMARY KEY (`exam_result_id`),
  ADD KEY `exam_result_user_exam_id` (`exam_result_user_exam_id`),
  ADD KEY `exam_result_question_id` (`exam_result_question_id`),
  ADD KEY `exam_result_selected_answer_id` (`exam_result_selected_answer_id`);

--
-- Chỉ mục cho bảng `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `exam_subject_id` (`exam_subject_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `feedback_user_id` (`feedback_user_id`);

--
-- Chỉ mục cho bảng `forumanswers`
--
ALTER TABLE `forumanswers`
  ADD PRIMARY KEY (`forum_answer_id`),
  ADD KEY `forum_question_id` (`forum_question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `forumquestions`
--
ALTER TABLE `forumquestions`
  ADD PRIMARY KEY (`forum_question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `leaderboard_user_id` (`leaderboard_user_id`);

--
-- Chỉ mục cho bảng `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`meeting_id`),
  ADD KEY `host_user_id` (`host_user_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `note_user_id` (`note_user_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payment_user_id` (`payment_user_id`);

--
-- Chỉ mục cho bảng `practiceresults`
--
ALTER TABLE `practiceresults`
  ADD PRIMARY KEY (`practice_result_id`),
  ADD KEY `practice_result_user_practice_id` (`practice_result_user_practice_id`),
  ADD KEY `practice_result_question_id` (`practice_result_question_id`),
  ADD KEY `practice_result_selected_answer_id` (`practice_result_selected_answer_id`);

--
-- Chỉ mục cho bảng `practicetestquestions`
--
ALTER TABLE `practicetestquestions`
  ADD PRIMARY KEY (`practice_test_question_id`),
  ADD KEY `practice_test_question_test_id` (`practice_test_question_test_id`),
  ADD KEY `practice_test_question_question_id` (`practice_test_question_question_id`);

--
-- Chỉ mục cho bảng `practicetests`
--
ALTER TABLE `practicetests`
  ADD PRIMARY KEY (`practice_test_id`),
  ADD KEY `practice_test_subject_id` (`practice_test_subject_id`);

--
-- Chỉ mục cho bảng `progresstracker`
--
ALTER TABLE `progresstracker`
  ADD PRIMARY KEY (`progress_tracker_id`),
  ADD KEY `progress_tracker_user_id` (`progress_tracker_user_id`),
  ADD KEY `progress_tracker_topic_id` (`progress_tracker_topic_id`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `question_type_id` (`question_type_id`),
  ADD KEY `question_topic_id` (`question_topic_id`);

--
-- Chỉ mục cho bảng `questiontypes`
--
ALTER TABLE `questiontypes`
  ADD PRIMARY KEY (`question_type_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `studymaterials`
--
ALTER TABLE `studymaterials`
  ADD PRIMARY KEY (`study_material_id`),
  ADD KEY `study_material_topic_id` (`study_material_topic_id`);

--
-- Chỉ mục cho bảng `studysessions`
--
ALTER TABLE `studysessions`
  ADD PRIMARY KEY (`study_session_id`),
  ADD KEY `study_session_topic_id` (`study_session_topic_id`),
  ADD KEY `study_session_user_id` (`study_session_user_id`);

--
-- Chỉ mục cho bảng `subjectcategories`
--
ALTER TABLE `subjectcategories`
  ADD PRIMARY KEY (`subject_category_id`),
  ADD KEY `subject_category_subject_id` (`subject_category_subject_id`),
  ADD KEY `subject_category_category_id` (`subject_category_category_id`);

--
-- Chỉ mục cho bảng `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Chỉ mục cho bảng `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `subscription_user_id` (`subscription_user_id`);

--
-- Chỉ mục cho bảng `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `topic_subject_id` (`topic_subject_id`);

--
-- Chỉ mục cho bảng `userbadges`
--
ALTER TABLE `userbadges`
  ADD PRIMARY KEY (`user_badge_id`),
  ADD KEY `user_badge_user_id` (`user_badge_user_id`),
  ADD KEY `user_badge_badge_id` (`user_badge_badge_id`);

--
-- Chỉ mục cho bảng `userchallenges`
--
ALTER TABLE `userchallenges`
  ADD PRIMARY KEY (`user_challenge_id`),
  ADD KEY `user_challenge_user_id` (`user_challenge_user_id`),
  ADD KEY `user_challenge_challenge_id` (`user_challenge_challenge_id`);

--
-- Chỉ mục cho bảng `userexams`
--
ALTER TABLE `userexams`
  ADD PRIMARY KEY (`user_exam_id`),
  ADD KEY `user_exam_exam_id` (`user_exam_exam_id`),
  ADD KEY `user_exam_user_id` (`user_exam_user_id`);

--
-- Chỉ mục cho bảng `userpractices`
--
ALTER TABLE `userpractices`
  ADD PRIMARY KEY (`user_practice_id`),
  ADD KEY `user_practice_test_id` (`user_practice_test_id`),
  ADD KEY `user_practice_user_id` (`user_practice_user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `ai_proctoring_sessions`
--
ALTER TABLE `ai_proctoring_sessions`
  MODIFY `proctor_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT cho bảng `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_rooms`
--
ALTER TABLE `chat_rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `dailychallenges`
--
ALTER TABLE `dailychallenges`
  MODIFY `daily_challenge_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `examquestions`
--
ALTER TABLE `examquestions`
  MODIFY `exam_question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `examresults`
--
ALTER TABLE `examresults`
  MODIFY `exam_result_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `forumanswers`
--
ALTER TABLE `forumanswers`
  MODIFY `forum_answer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `forumquestions`
--
ALTER TABLE `forumquestions`
  MODIFY `forum_question_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `leaderboard_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  MODIFY `participant_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `meetings`
--
ALTER TABLE `meetings`
  MODIFY `meeting_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `practiceresults`
--
ALTER TABLE `practiceresults`
  MODIFY `practice_result_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `practicetestquestions`
--
ALTER TABLE `practicetestquestions`
  MODIFY `practice_test_question_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `practicetests`
--
ALTER TABLE `practicetests`
  MODIFY `practice_test_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `progresstracker`
--
ALTER TABLE `progresstracker`
  MODIFY `progress_tracker_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `questiontypes`
--
ALTER TABLE `questiontypes`
  MODIFY `question_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `studymaterials`
--
ALTER TABLE `studymaterials`
  MODIFY `study_material_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `studysessions`
--
ALTER TABLE `studysessions`
  MODIFY `study_session_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `subjectcategories`
--
ALTER TABLE `subjectcategories`
  MODIFY `subject_category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `userbadges`
--
ALTER TABLE `userbadges`
  MODIFY `user_badge_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `userchallenges`
--
ALTER TABLE `userchallenges`
  MODIFY `user_challenge_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `userexams`
--
ALTER TABLE `userexams`
  MODIFY `user_exam_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `userpractices`
--
ALTER TABLE `userpractices`
  MODIFY `user_practice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ai_proctoring_sessions`
--
ALTER TABLE `ai_proctoring_sessions`
  ADD CONSTRAINT `fk_proctor_userexam` FOREIGN KEY (`user_exam_id`) REFERENCES `userexams` (`user_exam_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`answer_question_id`) REFERENCES `questions` (`question_id`);

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_chat_room` FOREIGN KEY (`room_id`) REFERENCES `chat_rooms` (`room_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_chat_user` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `examquestions`
--
ALTER TABLE `examquestions`
  ADD CONSTRAINT `examquestions_ibfk_1` FOREIGN KEY (`exam_question_exam_id`) REFERENCES `exams` (`exam_id`),
  ADD CONSTRAINT `examquestions_ibfk_2` FOREIGN KEY (`exam_question_question_id`) REFERENCES `questions` (`question_id`);

--
-- Các ràng buộc cho bảng `examresults`
--
ALTER TABLE `examresults`
  ADD CONSTRAINT `examresults_ibfk_1` FOREIGN KEY (`exam_result_user_exam_id`) REFERENCES `userexams` (`user_exam_id`),
  ADD CONSTRAINT `examresults_ibfk_2` FOREIGN KEY (`exam_result_question_id`) REFERENCES `questions` (`question_id`),
  ADD CONSTRAINT `examresults_ibfk_3` FOREIGN KEY (`exam_result_selected_answer_id`) REFERENCES `answers` (`answer_id`);

--
-- Các ràng buộc cho bảng `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`exam_subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Các ràng buộc cho bảng `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`feedback_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `forumanswers`
--
ALTER TABLE `forumanswers`
  ADD CONSTRAINT `forumanswers_ibfk_1` FOREIGN KEY (`forum_question_id`) REFERENCES `forumquestions` (`forum_question_id`),
  ADD CONSTRAINT `forumanswers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `forumquestions`
--
ALTER TABLE `forumquestions`
  ADD CONSTRAINT `forumquestions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`leaderboard_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `meetingparticipants`
--
ALTER TABLE `meetingparticipants`
  ADD CONSTRAINT `meetingparticipants_ibfk_1` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`meeting_id`),
  ADD CONSTRAINT `meetingparticipants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`host_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`note_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`payment_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `practiceresults`
--
ALTER TABLE `practiceresults`
  ADD CONSTRAINT `practiceresults_ibfk_1` FOREIGN KEY (`practice_result_user_practice_id`) REFERENCES `userpractices` (`user_practice_id`),
  ADD CONSTRAINT `practiceresults_ibfk_2` FOREIGN KEY (`practice_result_question_id`) REFERENCES `questions` (`question_id`),
  ADD CONSTRAINT `practiceresults_ibfk_3` FOREIGN KEY (`practice_result_selected_answer_id`) REFERENCES `answers` (`answer_id`);

--
-- Các ràng buộc cho bảng `practicetestquestions`
--
ALTER TABLE `practicetestquestions`
  ADD CONSTRAINT `practicetestquestions_ibfk_1` FOREIGN KEY (`practice_test_question_test_id`) REFERENCES `practicetests` (`practice_test_id`),
  ADD CONSTRAINT `practicetestquestions_ibfk_2` FOREIGN KEY (`practice_test_question_question_id`) REFERENCES `questions` (`question_id`);

--
-- Các ràng buộc cho bảng `practicetests`
--
ALTER TABLE `practicetests`
  ADD CONSTRAINT `practicetests_ibfk_1` FOREIGN KEY (`practice_test_subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Các ràng buộc cho bảng `progresstracker`
--
ALTER TABLE `progresstracker`
  ADD CONSTRAINT `progresstracker_ibfk_1` FOREIGN KEY (`progress_tracker_user_id`) REFERENCES `account` (`user_id`),
  ADD CONSTRAINT `progresstracker_ibfk_2` FOREIGN KEY (`progress_tracker_topic_id`) REFERENCES `topics` (`topic_id`);

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`question_type_id`) REFERENCES `questiontypes` (`question_type_id`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`question_topic_id`) REFERENCES `topics` (`topic_id`);

--
-- Các ràng buộc cho bảng `studymaterials`
--
ALTER TABLE `studymaterials`
  ADD CONSTRAINT `studymaterials_ibfk_1` FOREIGN KEY (`study_material_topic_id`) REFERENCES `topics` (`topic_id`);

--
-- Các ràng buộc cho bảng `studysessions`
--
ALTER TABLE `studysessions`
  ADD CONSTRAINT `studysessions_ibfk_1` FOREIGN KEY (`study_session_topic_id`) REFERENCES `topics` (`topic_id`),
  ADD CONSTRAINT `studysessions_ibfk_2` FOREIGN KEY (`study_session_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `subjectcategories`
--
ALTER TABLE `subjectcategories`
  ADD CONSTRAINT `subjectcategories_ibfk_1` FOREIGN KEY (`subject_category_subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `subjectcategories_ibfk_2` FOREIGN KEY (`subject_category_category_id`) REFERENCES `categories` (`category_id`);

--
-- Các ràng buộc cho bảng `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`subscription_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`topic_subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Các ràng buộc cho bảng `userbadges`
--
ALTER TABLE `userbadges`
  ADD CONSTRAINT `userbadges_ibfk_1` FOREIGN KEY (`user_badge_user_id`) REFERENCES `account` (`user_id`),
  ADD CONSTRAINT `userbadges_ibfk_2` FOREIGN KEY (`user_badge_badge_id`) REFERENCES `badges` (`badge_id`);

--
-- Các ràng buộc cho bảng `userchallenges`
--
ALTER TABLE `userchallenges`
  ADD CONSTRAINT `userchallenges_ibfk_1` FOREIGN KEY (`user_challenge_user_id`) REFERENCES `account` (`user_id`),
  ADD CONSTRAINT `userchallenges_ibfk_2` FOREIGN KEY (`user_challenge_challenge_id`) REFERENCES `dailychallenges` (`daily_challenge_id`);

--
-- Các ràng buộc cho bảng `userexams`
--
ALTER TABLE `userexams`
  ADD CONSTRAINT `userexams_ibfk_1` FOREIGN KEY (`user_exam_exam_id`) REFERENCES `exams` (`exam_id`),
  ADD CONSTRAINT `userexams_ibfk_2` FOREIGN KEY (`user_exam_user_id`) REFERENCES `account` (`user_id`);

--
-- Các ràng buộc cho bảng `userpractices`
--
ALTER TABLE `userpractices`
  ADD CONSTRAINT `userpractices_ibfk_1` FOREIGN KEY (`user_practice_test_id`) REFERENCES `practicetests` (`practice_test_id`),
  ADD CONSTRAINT `userpractices_ibfk_2` FOREIGN KEY (`user_practice_user_id`) REFERENCES `account` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
