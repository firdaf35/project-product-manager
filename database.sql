-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `product_manager` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `product_manager`;

-- Struktur tabel untuk `products`
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal (sample dataset florist)
INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`) VALUES
(1, 'Buket Mawar Red Velvet', 'Buket Bunga', 150000.00, 12),
(2, 'Vas Keramik Lily Putih', 'Aksesoris & Vas', 75000.00, 8),
(3, 'Buket Anggrek Bulan', 'Buket Bunga', 250000.00, 5),
(4, 'Bunga Meja Sunflower', 'Bunga Meja', 120000.00, 10);