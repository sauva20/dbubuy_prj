-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 09, 2025 at 01:59 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `liputan`
--

CREATE TABLE `liputan` (
  `id` int NOT NULL,
  `media_name` varchar(100) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `liputan`
--

INSERT INTO `liputan` (`id`, `media_name`, `description`, `image`, `link_url`, `created_at`) VALUES
(2, 'Kota Subang', 'Daging ayam yang telah dibersihkan dan dibumbui dibungkus dengan daun pisang kemudian dipendam dalam bara sekam selama 6 jam. Proses memasak dengan cara tradisional ini membuat cita rasa warisan leluhur tetap terjaga.', 'assets/img/media_1764643161_unnamed.jpg', 'https://youtu.be/mrwNLzlRvos?si=4M48UrULnhpMyure', '2025-12-02 02:39:21'),
(3, 'Trans7 Official', 'Bubuy Ayam Kuliner khas Subang ini adalah olahan daging ayam yang dimasak dengan memendam daging ayam ke dalam bara sekam.', 'assets/img/media_1764643499_channels4_profile.jpg', 'https://youtu.be/f01tCQrA-K0?si=7zbJdk80BV29Sy9M', '2025-12-02 02:44:59'),
(4, 'Museum Gedung Sate', 'Ayamnya lembut..\r\nRasanya nikmat..\r\nPastinya menggugah selera..\r\n\r\nTapi Sahabat tau ngga? Kalau masak ayam ini bisa sampe 12 jam, lho! \r\n😮\r\n\r\nNah, kali ini Tatalepa bersama edukator Dery lagi ada di Kabupaten Subang buat menelusuri kuliner ayam legendaris ini. \r\nSpoiler alert, ternyata D\'bubuy itu diambil dari cara buatnya yaitu di bubuy! 😋', 'assets/img/media_1764643786_unnamed_2.jpg', 'https://youtu.be/OgwQmj3df2U?si=3WhaeyTGBiDpJDdY', '2025-12-02 02:49:46'),
(5, 'KOMPASTV', 'Bubuy Hayam. Jangan terkecoh dengan namanya. Dalam bahasa Sunda Bubuy artinya memasak dalam bara sekam. Bubuy Hayam berati olahan daging ayam yang dimasak di dalam bara.\r\n\r\nSaya langsung mendatangi Bubuy Hayam yang paling terkenal di Subang Jawa Barat yakni De Bubuy Ma Atik yang berlokasi di Gang Kenanga Dua kabupaten Subang. Saya disambut sang pemiilik De Bubuy Yaitu Reynard Smara Mahardikka atau lebih akrab disapa Kang Eenk.', 'assets/img/media_1764643855_channels4_profile_2.jpg', 'https://youtu.be/wLvb0ToWra4?si=KH54PsEpb2aF_sNj', '2025-12-02 02:50:55'),
(6, 'CNN Indonesia', 'Jika sebelumnya kita mengenal kuliner berbagai olahan ayam, mulai ayam goreng, ayam bakar, pais ayam, kini ada satu lagi varian olahan ayam yang mulai diburu khususnya warga Bandung, yakni bubuy hayam.\r\n\r\nSesuai namanya, olahan ayam ini dimasak dengan cara dibubuy alias dipendam dalam sekam. Menurut denny, pembuatnya, dengan memendam ayam selama kurang lebih empat jam ini, aroma maupun bumbu rempah ini menjadi sangat terasa.', 'assets/img/media_1764643951_channels4_profile_3.jpg', 'https://youtu.be/a3rvD0z7V9w?si=YJKH--gRvQWQtGRh', '2025-12-02 02:52:31'),
(7, 'Buletin iNews GTV', 'Program berita harian yang menyajikan berbagai peristiwa terkini secara cepat dan akurat dari seluruh Indonesia. Mengupas berbagai masalah yang tengah hangat di masyarakat, baik di bidang sosial, ekonomi, perkotaan, hingga dunia hukum dan politik. Semuanya dikemas secara mendalam, menyentuh tapi tetap kritis. Buletin iNews Pagi, Siang, dan Malam juga dilengkapi dengan berita-berita hiburan serta feature unik baik dari dalam dan luar negeri yang disajikan secara humanis.', 'assets/img/media_1764644027_channels4_profile_4.jpg', 'https://youtu.be/CrugKpIyo5Q?si=VD78mNigL6x-Gepd', '2025-12-02 02:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `username`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 5, 'Admin', 'Unbanned User', 'Mengubah status user ID: 7', '127.0.0.1', '2025-12-07 15:45:53'),
(2, 5, 'Admin', 'Banned User', 'Mengubah status user ID: 8', '127.0.0.1', '2025-12-07 15:46:29'),
(3, 5, 'Admin', 'Unbanned User', 'Mengubah status user ID: 8', '127.0.0.1', '2025-12-07 15:46:43'),
(4, 5, 'Admin', 'Update Status', 'Mengubah status Order #ORD-1764753770-468 menjadi success', '127.0.0.1', '2025-12-07 15:47:07'),
(5, 5, 'Admin', 'Hapus Media', 'Menghapus media: On The Spot', '127.0.0.1', '2025-12-07 15:51:12'),
(6, 5, 'Admin', 'Edit Produk', 'Mengupdate data produk: Bubuy Bebek enak', '127.0.0.1', '2025-12-07 15:57:07'),
(7, 5, 'Admin', 'Edit Produk', 'Mengupdate data produk: Bubuy Bebek', '127.0.0.1', '2025-12-07 15:57:41'),
(8, 5, 'Admin', 'Tambah Produk', 'Menambahkan produk baru: Bubuy Ayam Kampungas', '127.0.0.1', '2025-12-07 16:04:02'),
(9, 5, 'Admin', 'Edit Produk', 'Mengupdate data produk: Bubuy Ayam Kampunan', '127.0.0.1', '2025-12-07 16:05:10'),
(10, 5, 'Admin', 'Hapus Produk', 'Menghapus produk: Bubuy Ayam Kampunan', '127.0.0.1', '2025-12-07 16:05:26'),
(11, 5, 'Admin', 'Edit Produk', 'Mengupdate data produk: Ayam Geprek', '127.0.0.1', '2025-12-07 16:06:44'),
(12, 5, 'Admin', 'Hapus Produk', 'Menghapus produk: Ayam Geprek', '127.0.0.1', '2025-12-07 16:07:19'),
(13, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Nama: \'Bubuy Bebek\' -> \'Bubuy Bebek Enak \', Harga: Rp 160,000 -> Rp 160,001', '127.0.0.1', '2025-12-07 16:14:39'),
(14, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Nama: \'Bubuy Bebek Enak \' -> \'Bubuy Bebek\', Harga: Rp 160,001 -> Rp 160,000', '127.0.0.1', '2025-12-07 16:21:49'),
(15, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Harga: Rp 160.000 -> Rp 160.011', '127.0.0.1', '2025-12-07 16:27:21'),
(16, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Harga: Rp 160.011 -> Rp 160.010', '127.0.0.1', '2025-12-07 16:27:59'),
(17, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Nama: \'Bubuy Bebek\' -> \'Bubuy Bebek baru\' | Harga: Rp 160.010 -> Rp 160.000 | Estimasi: \'5 hari\' -> \'4 hari\' | Deskripsi diubah', '127.0.0.1', '2025-12-07 16:28:29'),
(18, 5, 'Admin', 'Edit Produk', 'Mengedit Produk ID #4. Detail: Nama: \'Bubuy Bebek baru\' -> \'Bubuy Bebek \' | Estimasi: \'4 hari\' -> \'5 hari\'', '127.0.0.1', '2025-12-07 16:28:55'),
(19, 5, 'Admin', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:10:54'),
(20, 6, 'cust', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:11:31'),
(21, 5, 'Admin', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:19:23'),
(22, 6, 'cust', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:19:57'),
(23, 9, 'prabu pacarnya qisty cantik', 'Login', 'User login', '127.0.0.1', '2025-12-08 03:29:52'),
(24, 9, 'prabu pacarnya qisty cantik', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:29:56'),
(25, 5, 'Admin', 'Login', 'User login', '127.0.0.1', '2025-12-08 03:36:42'),
(26, 5, 'Admin', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:36:44'),
(27, 9, 'prabu pacarnya qisty cantik', 'Login', 'User login', '127.0.0.1', '2025-12-08 03:37:00'),
(28, 9, 'prabu pacarnya qisty cantik', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 03:37:03'),
(29, 5, 'Admin', 'Login', 'User login', '127.0.0.1', '2025-12-08 10:49:35'),
(30, 5, 'Admin', 'Logout', 'User keluar dari sistem', '127.0.0.1', '2025-12-08 10:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(50) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `total_amount`, `status`, `snap_token`, `created_at`) VALUES
('ORD-1764600968-323', 'cust', '2qistysauva@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 160000.00, 'pending', '31e70719-9f91-46e5-9d2e-95b754b160ae', '2025-12-01 14:56:09'),
('ORD-1764601284-239', 'cust', '2qistysauva@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 160000.00, 'pending', '1a50479f-49b9-4e96-bdc1-df9c6cf31df8', '2025-12-01 15:01:25'),
('ORD-1764601953-633', 'cust', '2qistysauva@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 425000.00, 'success', '5db434f3-ac3f-41a7-a179-ee3e3eaef937', '2025-12-01 15:12:34'),
('ORD-1764642608-294', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 120000.00, 'pending', '51b3ce78-eb2b-4cd8-8f01-86666f966f73', '2025-12-02 02:30:10'),
('ORD-1764739935-480', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 160000.00, 'pending', 'adc980ce-0aaa-447d-b284-b1d166fb2624', '2025-12-03 05:32:22'),
('ORD-1764740299-352', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 120000.00, 'pending', '9765c6f3-1552-4b68-bcdf-522e9607121b', '2025-12-03 05:38:21'),
('ORD-1764740709-855', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 120000.00, 'pending', 'ff854a86-be8c-4a62-92d5-9fa5746d191d', '2025-12-03 05:45:10'),
('ORD-1764740842-134', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 120000.00, 'pending', 'f974c16f-da54-4461-ad77-427697f04b87', '2025-12-03 05:47:24'),
('ORD-1764749042-555', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 280000.00, 'proses', 'a196ce0b-fe87-456a-bb56-e6a8e93d2411', '2025-12-03 08:04:04'),
('ORD-1764753749-900', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 160000.00, 'pending', '11b420c5-778f-4738-b603-bde65f27bfd0', '2025-12-03 09:22:32'),
('ORD-1764753770-468', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 240000.00, 'success', '6ef60405-7ad5-470e-a48b-d1b97e2685a5', '2025-12-03 09:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(4, 'ORD-1764600968-323', 4, 'Bubuy Bebek', 160000.00, 1),
(5, 'ORD-1764601284-239', 4, 'Bubuy Bebek', 160000.00, 1),
(6, 'ORD-1764601953-633', 4, 'Bubuy Bebek', 160000.00, 1),
(7, 'ORD-1764601953-633', 3, 'Bubuy Ikan ', 120000.00, 1),
(8, 'ORD-1764601953-633', 2, 'Bubuy Ayam Kampung', 145000.00, 1),
(9, 'ORD-1764642608-294', 3, 'Bubuy Ikan ', 120000.00, 1),
(10, 'ORD-1764739935-480', 4, 'Bubuy Bebek', 160000.00, 1),
(11, 'ORD-1764740299-352', 3, 'Bubuy Ikan ', 120000.00, 1),
(12, 'ORD-1764740709-855', 3, 'Bubuy Ikan ', 120000.00, 1),
(13, 'ORD-1764740842-134', 3, 'Bubuy Ikan ', 120000.00, 1),
(14, 'ORD-1764749042-555', 4, 'Bubuy Bebek', 160000.00, 1),
(15, 'ORD-1764749042-555', 3, 'Bubuy Ikan ', 120000.00, 1),
(16, 'ORD-1764753749-900', 4, 'Bubuy Bebek', 160000.00, 1),
(17, 'ORD-1764753770-468', 3, 'Bubuy Ikan ', 120000.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,0) NOT NULL,
  `estimation` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `estimation`, `image`, `created_at`) VALUES
(2, 'Bubuy Ayam Kampung', 'bubuy dbuat menggunakan ayam kampung berkualitas tinggi dengan menghadirkan keauthentikan rasa yang stabis menggunakan resep turun temurun', 145000, '2 hari', 'assets/img/1764122764_file_2025-11-24_14.13.56.png', '2025-11-26 02:06:04'),
(3, 'Bubuy Ikan ', 'menggunakan ikna pilihan dengan menyajikan rasa khas dbubuy maatik', 120000, '1 hari', 'assets/img/1764122811_file_2025-11-24_14.13.56.png', '2025-11-26 02:06:51'),
(4, 'Bubuy Bebek ', 'Bubuy dengan menu terbatas yang menghadirkan rasa terbaik dan kualitas yang terjaga..', 160000, '5 hari', 'assets/img/1764122865_file_2025-11-24_14.13.56.png', '2025-11-26 02:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_whatsapp` varchar(25) NOT NULL,
  `wa_verified_at` timestamp NULL DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `kategori` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_banned` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `no_whatsapp`, `wa_verified_at`, `username`, `password`, `kategori`, `created_at`, `is_banned`) VALUES
(1, 'Qisty Sauva', '0895423019908', '2025-11-11 11:46:17', 'QistyCantik', '$2y$10$C8DKtTRT7OBavBblCQA2R.7FubDRyQGZzmEhSRzNtUP13l6yahfCS$2y$10$mhITu3Ju/RtGYWR3ffazBuHArfAqnvrHzGVyfN7riSu6iA..lcvZy', 'admin', '2025-11-11 11:49:22', 0),
(2, 'Prabu Alam', '085156677227', '2025-11-11 11:49:36', 'PacarQisty', '$2y$10$C8DKtTRT7OBavBblCQA2R.7FubDRyQGZzmEhSRzNtUP13l6yahfCS', 'customer', '2025-11-11 11:50:16', 0),
(3, 'Prabu Sauva', '085880278506', NULL, NULL, '$2y$10$BJyziEYMSgB3pXaUOHr.8e6hcEwb8Y555nXe77b9HINbHBLfl8V/e', 'customer', '2025-11-11 12:58:32', 0),
(5, 'Admin', '081122334455', '2025-11-19 04:31:14', 'QistyCantikBanget', '$2y$10$UyXVsrOxiqYXI7OGzElz2udxNhKBV6qHZdFQwrxXGDD0xVV6x27Hm', 'admin', '2025-11-19 04:31:14', 0),
(6, 'cust', '081234567890', NULL, NULL, '$2y$10$BJyziEYMSgB3pXaUOHr.8e6hcEwb8Y555nXe77b9HINbHBLfl8V/e', 'customer', '2025-11-24 13:26:36', 0),
(7, 'dimas', '0000000000', NULL, 'dimas', '$2y$10$3Elbtu5YIyI9WddIefX3T.IwKG2u.sfzK7V.jYlbMUJJfVJecnriq', 'customer', '2025-12-02 03:29:00', 0),
(8, 'qisty', '123456789012', NULL, 'qisty', '$2y$10$3tlKOnw7OqhgwIgA/YEjXukTzw7O0GwbB8Ues/.e31z.VFgYp/IIu', 'customer', '2025-12-03 07:10:34', 0),
(9, 'prabu pacarnya qisty cantik', '081122334411', NULL, 'prabu pacarnya qisty cantik', '$2y$10$XfOjxA8W16hz3aM/.Y/cjO6pXLzTtCLE0tUUITsghUP5BSNLo3tvC', 'customer', '2025-12-08 03:29:39', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `liputan`
--
ALTER TABLE `liputan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_whatsapp` (`no_whatsapp`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `liputan`
--
ALTER TABLE `liputan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
