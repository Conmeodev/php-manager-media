-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 05, 2024 lúc 02:08 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `upload`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `_name` text DEFAULT NULL,
  `_thumb` text DEFAULT NULL,
  `_uid` text DEFAULT NULL,
  `_byid` text DEFAULT NULL,
  `_time` text DEFAULT NULL,
  `_share` text DEFAULT NULL,
  `_list_share` text DEFAULT NULL,
  `_token` text DEFAULT NULL,
  `old_name` text DEFAULT NULL,
  `_type` text DEFAULT NULL,
  `_dir` text DEFAULT NULL,
  `_status` text DEFAULT NULL,
  `_size` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `_uid` int(11) DEFAULT NULL,
  `_name` text DEFAULT NULL,
  `_timecreate` int(11) DEFAULT NULL,
  `_share` text DEFAULT NULL,
  `_private` text DEFAULT NULL,
  `_byid` int(11) DEFAULT NULL,
  `_token` text DEFAULT NULL,
  `_status` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `_time` int(11) DEFAULT NULL,
  `_token` text DEFAULT NULL,
  `_status` text DEFAULT NULL,
  `_content` text DEFAULT NULL,
  `_device` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `_user` text NOT NULL,
  `_pass` text NOT NULL,
  `_username` text DEFAULT NULL,
  `_email` text DEFAULT NULL,
  `_timecreater` text NOT NULL,
  `_birthday` text DEFAULT NULL,
  `_lv` text DEFAULT NULL,
  `_mod` text DEFAULT NULL,
  `_token` text DEFAULT NULL,
  `_showpass` text DEFAULT NULL,
  `_share` text DEFAULT NULL,
  `_list_share` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `_user`, `_pass`, `_username`, `_email`, `_timecreater`, `_birthday`, `_lv`, `_mod`, `_token`, `_showpass`, `_share`, `_list_share`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'admin@gmail.com', '1720178238', NULL, '010', NULL, 'a3474cd8324569ad8e3f5ce8457f1c34', '123456', 'public', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
