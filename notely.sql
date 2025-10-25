-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 10:05 AM
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
-- Database: `notely`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workspace_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `content` mediumtext DEFAULT NULL,
  `is_favorite` tinyint(1) DEFAULT 0,
  `is_trashed` tinyint(1) DEFAULT 0,
  `attachment_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_pinned` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `user_id`, `workspace_id`, `parent_id`, `title`, `content`, `is_favorite`, `is_trashed`, `attachment_path`, `created_at`, `updated_at`, `is_pinned`, `views`, `sort_order`) VALUES
(1, 1, NULL, NULL, 'Skripsi', '<ul><li>Buat surat izin penelitian</li><li>Wawancara dengan pemilik dan pengguna</li><li>Cari 2 Skripsi yang 1 Arah</li><li>Deadline pada saat bimbingan pertama</li></ul><div><br></div>', 0, 0, '/assets/uploads/att_1_1_1761376248.png', '2025-10-25 07:08:33', '2025-10-25 07:29:05', 0, 0, 0),
(3, 1, NULL, NULL, 'Pemograman Web ( UTS )', '<ul><li>Buat website sistem menggunakan php tanpa framework</li><li>Upload di google drive</li><li>Screenshot tampilan website</li><li>Buatkan catatan cara menjalankan website nya</li><li>Deadline pada saat uts</li></ul><div><div><br></div></div>', 0, 0, NULL, '2025-10-25 07:24:23', '2025-10-25 07:28:40', 0, 0, 0),
(4, 1, NULL, NULL, 'Testing dan QA Perangkat Lunak', '<div><br></div><ul><li>Buat Activity Diagram Sistem Informasi Klinik 24 Jam sebagai kepala gudang</li><li>Buat Sequence Diagram Sistem Informasi Klinik 24 Jam sebagai kepala gudang</li><li>Buat Class Diagram Sistem Informasi Klinik 24 Jam sebagai kepala gudang</li><li>Deadline belum diketahui</li></ul>', 0, 0, NULL, '2025-10-25 07:29:52', '2025-10-25 07:33:01', 0, 0, 0),
(5, 1, NULL, NULL, 'Pemograman Web ( Project )', '<ul><li>Revisi inventaris kantor menjadi 2 role</li></ul>', 0, 0, NULL, '2025-10-25 07:33:12', '2025-10-25 07:33:57', 0, 0, 0),
(6, 1, NULL, NULL, 'Manajemen Proyek Informatika', '<ul><li>Buat Jurnal&nbsp;</li></ul>', 0, 0, NULL, '2025-10-25 07:34:16', '2025-10-25 07:34:42', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `note_tags`
--

CREATE TABLE `note_tags` (
  `note_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remind_at` datetime NOT NULL,
  `message` varchar(255) NOT NULL,
  `sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shared_notes`
--

CREATE TABLE `shared_notes` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `target_user_id` int(11) DEFAULT NULL,
  `target_email` varchar(191) DEFAULT NULL,
  `permission` enum('read','write') NOT NULL DEFAULT 'read',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Ario', 'ario.ardhi@gmail.com', '$2y$10$r26UDJCLnlZPXdmH2QdAdOBTTWV1J455ErIgyKIwyTvGLtKkabdPy', '2025-10-25 06:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `user_id`, `name`, `created_at`) VALUES
(1, 1, 'Skripsi', '2025-10-25 07:02:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `workspace_id` (`workspace_id`),
  ADD KEY `is_pinned` (`is_pinned`),
  ADD KEY `updated_at` (`updated_at`);
ALTER TABLE `notes` ADD FULLTEXT KEY `ft_title_content` (`title`,`content`);

--
-- Indexes for table `note_tags`
--
ALTER TABLE `note_tags`
  ADD PRIMARY KEY (`note_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_id` (`note_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `remind_at` (`remind_at`,`sent`);

--
-- Indexes for table `shared_notes`
--
ALTER TABLE `shared_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_id` (`note_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `target_user_id` (`target_user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_tag_unique` (`user_id`,`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name_unique` (`user_id`,`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shared_notes`
--
ALTER TABLE `shared_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `note_tags`
--
ALTER TABLE `note_tags`
  ADD CONSTRAINT `note_tags_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reminders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shared_notes`
--
ALTER TABLE `shared_notes`
  ADD CONSTRAINT `shared_notes_ibfk_1` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_notes_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shared_notes_ibfk_3` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `workspaces_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
