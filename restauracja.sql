-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 09 Gru 2021, 17:12
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `restauracja`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `category` enum('pizza','beer','','') NOT NULL,
  `price` double(6,2) NOT NULL,
  `photo` varchar(40) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `menu`
--

INSERT INTO `menu` (`id`, `name`, `category`, `price`, `photo`, `description`) VALUES
(1, 'margherita', 'pizza', 30.99, 'pizzaBackground.jpg', 'Składniki:\r\n-ser\r\n-ser\r\n-ser\r\n-ser'),
(2, 'margherita', 'pizza', 25.58, 'mar.jpg', 'Wyśmienita pizza.\r\nPolecam!\r\nBardzo!'),
(3, 'Piwo wolnościowe', 'beer', 7.99, 'piwo_wolnosciowe.png', 'Bardzo dobre'),
(4, 'Polska wolność', 'beer', 7.99, 'polska_wolnosc.png', 'Bardzo dobre!'),
(5, 'Paszport Mentzena', 'beer', 9.99, 'paszport_mentzena.png', 'Wyśmienite');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `idOrders` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `price` double(6,2) NOT NULL,
  `status` enum('W trakcie realizcaji','zrealizowano','anulowano','') NOT NULL,
  `number` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `permission` enum('user','admin','employee') NOT NULL DEFAULT 'user',
  `city` varchar(40) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `phone` varchar(9) DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(64) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `permission`, `city`, `address`, `phone`, `isActive`, `password`, `email`) VALUES
(16, 'Jan', 'user', 'Katowice', 'Mokra 5', '156485555', 1, '7c4a8d09ca3762af61e59520943dc26494f8941b', 'J@gmail.com'),
(17, 'admin', 'admin', NULL, NULL, NULL, 1, '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'user@test.pl');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
