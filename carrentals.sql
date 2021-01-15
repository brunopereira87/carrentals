-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 15-Jan-2021 às 13:58
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `carrentals`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cars`
--

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `company` varchar(60) DEFAULT NULL,
  `rental_price` double NOT NULL,
  `rented` tinyint(1) UNSIGNED DEFAULT '0',
  `rental_user_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cars`
--

INSERT INTO `cars` (`id`, `name`, `plate`, `company`, `rental_price`, `rented`, `rental_user_id`) VALUES
(1, 'Fox 2015', 'BC102-AM8', 'Volkswagen', 100, 0, NULL),
(4, 'Fox', 'GA102-AM8', 'Volkswagen', 100, 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rentals`
--

DROP TABLE IF EXISTS `rentals`;
CREATE TABLE IF NOT EXISTS `rentals` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rent_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` tinyint(1) DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(2, 'Bruno Pereira', 'bruno@email.com', '$2y$10$Nlhw8SEYPc7mUwSBdECCW./27rcN0CkvryK3we51zLZtPUcze2dmO', 1),
(3, 'Bruno Pereira', 'bruno2@email.com', '$2y$10$KXpigpl9JATj8j2ieiBBs.fjApa2er0PzSXbudTFfrIJELFmBUFoy', 2),
(4, 'Marina Pereira', 'marina@email.com', '$2y$10$8SjRmW7lZxNpBvT/TlIWs.FWZo.6GZXylAUOFYA5QWdgBIzmXPQKe', 2),
(5, 'Marcos Silva', 'marcos@email.com', '$2y$10$AL0PtbTiKe0dTHiHjzOAte4vmz4bk2e3412a7WTBIMtn5VbczDkQ2', 2),
(6, 'Murilo Cardoso', 'murilo@email.com', '$2y$10$kHq0Y0ap2fkCIcAjCE3d.egrfJeHB3HDZBtd7.XH4AyQptQyc0Vqy', 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
