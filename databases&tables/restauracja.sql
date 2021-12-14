-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 14 Gru 2021, 16:19
-- Wersja serwera: 10.4.13-MariaDB
-- Wersja PHP: 7.4.8

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
-- Struktura tabeli dla tabeli `discounts`
--

CREATE TABLE `discounts` (
  `code` varchar(16) NOT NULL,
  `discount` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `discounts`
--

INSERT INTO `discounts` (`code`, `discount`) VALUES
('Brak', 0),
('inflacja10', -10),
('naKosztFirmy', 100);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `category` enum('Pizza','Piwo') NOT NULL,
  `price` double(6,2) NOT NULL,
  `photo` varchar(40) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `menu`
--

INSERT INTO `menu` (`id`, `name`, `category`, `price`, `photo`, `description`) VALUES
(1, 'Texas', 'Pizza', 30.99, 'texas.jpg', 'Składniki:\r\n-ser\r\n-cebula\r\n-boczek\r\n-kukurydza'),
(2, 'margherita', 'Pizza', 25.58, 'mar.jpg', 'Wyśmienita pizza.\r\nPolecam!\r\nBardzo!'),
(3, 'Piwo wolnościowe', 'Piwo', 7.99, 'piwo_wolnosciowe.png', 'Bardzo dobre'),
(4, 'Polska wolność', 'Piwo', 7.99, 'polska_wolnosc.png', 'Bardzo dobre!'),
(5, 'Paszport Mentzena', 'Piwo', 9.99, 'paszport_mentzena.png', 'Wyśmienite'),
(6, 'Wiejska', 'Pizza', 27.10, 'wiejska.jpg', 'Pizza prosto ze wsi!'),
(7, 'Diavolo', 'Pizza', 28.99, 'diavolo.jpg', 'Bardzo dobra w smaku!'),
(8, 'Peperoni', 'Pizza', 21.79, 'peperoni.jpg', 'Klasyk!'),
(9, 'Bezobjawowe', 'Piwo', 6.99, 'bezobjawowe.png', 'Piwo 0%'),
(10, 'White IPA Matters', 'Piwo', 10.99, 'white_ipa_matters.png', 'Doskonałe w smaku'),
(11, 'Czekoladowa rozkosz', 'Pizza', 32.00, 'czekoladowa.jpg', 'Rozpływa się w ustach');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `idOrders` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `number` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`idOrders`, `idUser`, `idProduct`, `number`) VALUES
(1, 16, 1, 2),
(1, 16, 2, 1),
(1, 16, 3, 1),
(1, 16, 4, 1),
(2, 16, 2, 1),
(2, 16, 6, 1),
(3, 16, 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ordersdetails`
--

CREATE TABLE `ordersdetails` (
  `idOrders` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `city` varchar(40) CHARACTER SET utf8 NOT NULL,
  `address` varchar(64) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(9) CHARACTER SET utf8 NOT NULL,
  `discountCode` varchar(16) NOT NULL DEFAULT 'Brak',
  `status` enum('W trakcie realizacji','zrealizowano','anulowano','') CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `ordersdetails`
--

INSERT INTO `ordersdetails` (`idOrders`, `name`, `city`, `address`, `phone`, `discountCode`, `status`) VALUES
(1, 'Jan', 'Gliwice', 'Polna 2', '987654321', 'inflacja10', 'W trakcie realizacji'),
(2, 'synJana', 'Katowice', 'Lesna 2', '123456789', 'naKosztFirmy', 'W trakcie realizacji'),
(3, 'Jan', 'Gliwice', 'Polna 2', '987654321', 'Brak', 'W trakcie realizacji');

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
(17, 'admin', 'admin', NULL, NULL, NULL, 1, '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'user@test.pl'),
(18, 'Jakub', 'employee', 'Gliwice', 'tak 5', '168455555', 1, '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', 'xd@op.pl'),
(19, NULL, 'user', NULL, NULL, NULL, 1, '22e60f213a388c2cc2872dcb2c9fedee66165bc8', 'Patryk@test.pl');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`code`);

--
-- Indeksy dla tabeli `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idOrders`,`idUser`,`idProduct`) USING BTREE;

--
-- Indeksy dla tabeli `ordersdetails`
--
ALTER TABLE `ordersdetails`
  ADD PRIMARY KEY (`idOrders`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
