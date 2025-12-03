-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 01:42 AM
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
('ORD-1764642608-294', 'cust', 'prabualamtian@gmail.com', '081234567890', 'PICKUP - Ambil di Tempat', 120000.00, 'pending', '51b3ce78-eb2b-4cd8-8f01-86666f966f73', '2025-12-02 02:30:10');

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
(9, 'ORD-1764642608-294', 3, 'Bubuy Ikan ', 120000.00, 1);

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
(4, 'Bubuy Bebek', 'Bubuy dengan menu terbatas yang menghadirkan rasa terbaik dan kualitas yang terjaga.', 160000, '5 hari', 'assets/img/1764122865_file_2025-11-24_14.13.56.png', '2025-11-26 02:07:45');

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
(7, 'dimas', '0000000000', NULL, 'dimas', '$2y$10$3Elbtu5YIyI9WddIefX3T.IwKG2u.sfzK7V.jYlbMUJJfVJecnriq', 'customer', '2025-12-02 03:29:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `liputan`
--
ALTER TABLE `liputan`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
