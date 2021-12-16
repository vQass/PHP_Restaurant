-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 16 Gru 2021, 15:41
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
  `category` enum('Pizza','Piwo') NOT NULL,
  `price` double(6,2) NOT NULL,
  `photo` varchar(40) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `menu`
--

INSERT INTO `menu` (`id`, `name`, `category`, `price`, `photo`, `description`) VALUES
(1, 'Texas', 'Pizza', 30.99, 'texas.jpg', 'Składniki:\r\n- sos barbecue\r\n- ser cheddar\r\n- czerwona cebula\r\n- boczek\r\n- kurczak\r\n- kukurydza\r\n- oregano'),
(2, 'Margherita', 'Pizza', 25.58, 'mar.jpg', 'Składniki:\r\n- sos pomidorowy\r\n- ser mozzarella\r\n- bazylia'),
(3, 'Piwo wolnościowe', 'Piwo', 7.99, 'piwo_wolnosciowe.png', 'Piwo pszeniczne, goryczka niska\r\nALK. 4 % | BLG 12'),
(4, 'Polska wolność', 'Piwo', 7.99, 'polska_wolnosc.png', 'Piwo jasne w stylu Imperial Witbier, goryczka niska\r\nALK. 7 % | BLG 18'),
(5, 'Paszport Mentzena', 'Piwo', 9.99, 'paszport_mentzena.png', 'Piwo jasne w stylu IPA, goryczka średnia\r\nALK. 6 % | BLG 15'),
(6, 'Wiejska', 'Pizza', 27.10, 'wiejska.jpg', 'Składniki:\r\n- sos pomidorowy\r\n- ser cheddar\r\n- szynka\r\n- baleron\r\n- boczek\r\n- kiełbasa\r\n- cebula\r\n- pieczarki'),
(7, 'Diavolo', 'Pizza', 28.99, 'diavolo.jpg', 'Składniki:\r\n- sos pomidorowy\r\n- pikantny ser pecorino\r\n- papryczka chilli\r\n- oliwki\r\n- salami'),
(8, 'Peperoni', 'Pizza', 21.79, 'peperoni.jpg', 'Składniki:\r\n- sos pomidorowy\r\n- ser mozzarella\r\n- pepperoni\r\n- bazylia'),
(9, 'Bezobjawowe', 'Piwo', 6.99, 'bezobjawowe.png', 'Piwo bezalkoholowe w stylu IPA, goryczka średnia\r\nALK. <0,5 % | BLG 7,8'),
(10, 'White IPA Matters', 'Piwo', 10.99, 'white_ipa_matters.png', 'Piwo jasne w stylu White IPA, goryczka średnia\r\nALK. 6 % | BLG 15'),
(11, 'Czekoladowa rozkosz', 'Pizza', 32.00, 'czekoladowa.jpg', 'Składniki:\r\n- czekolada\r\n- cukierki M&M\'s\r\n- batony Kit Kat oraz Milky Way\r\n- maliny');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
