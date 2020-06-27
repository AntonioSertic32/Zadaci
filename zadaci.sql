-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2020 at 05:04 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zadaci`
--

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `korisnik` varchar(50) NOT NULL,
  `opis` text NOT NULL,
  `datum` varchar(45) NOT NULL,
  `zadatak` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `ime` varchar(45) NOT NULL,
  `prezime` varchar(45) NOT NULL,
  `lozinka` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `korisnicko_ime` varchar(45) NOT NULL,
  `slika` varchar(50) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `prebivaliste` varchar(50) DEFAULT NULL,
  `datum_rodenja` varchar(50) DEFAULT NULL,
  `spol` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `lozinka`, `email`, `korisnicko_ime`, `slika`, `tel`, `bio`, `prebivaliste`, `datum_rodenja`, `spol`) VALUES
(1, 'Mirko', 'Mirkić', 'a', 'a', 'Mirkec', '4.jpg', '0917851234', 'Imam 18 godina i dolazim iz Dubrovnika. Studiram računarstvo na VSMTI i u slobodno vrijeme volim fotografirati. Imam najbolju sestru!', 'Dubrovnik', '2002-05-03T23:00:00.000Z', NULL),
(2, 'Darko', 'Darkić', 'b', 'b', 'Darkec', '2.jpg', '092741236', 'Ja sam novi ovdje ;)', 'Osijek', '1998-10-09T22:00:00.000Z', 'Muško'),
(3, 'Slavko', 'Slavkić', 'c', 'c', 'Slavkec', '3.jpg', '093963258', NULL, 'Zagreb', '1991-12-10T22:00:00.000Z', 'Muško'),
(13, 'Judita', 'Lijić', 'd', 'd', 'Judica', '7.jpg', NULL, NULL, 'Varaždin', NULL, 'Žensko');

-- --------------------------------------------------------

--
-- Table structure for table `zadatak`
--

CREATE TABLE `zadatak` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `datum_pocetka` varchar(50) NOT NULL,
  `datum_zavrsetka` varchar(50) NOT NULL,
  `izvrsitelj` int(11) NOT NULL,
  `kreator` int(11) NOT NULL,
  `stanje` int(11) NOT NULL,
  `opis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `zadatak`
--

INSERT INTO `zadatak` (`id`, `naziv`, `datum_pocetka`, `datum_zavrsetka`, `izvrsitelj`, `kreator`, `stanje`, `opis`) VALUES
(1, 'Spremiti sobu', '2020-06-01T22:00:00.000Z', '2020-06-03T22:00:00.000Z', 2, 1, 1, 'Spremiti robu u sobi.'),
(2, 'Oprat suđe', '2020-05-27T22:00:00.000Z', '2020-05-28T22:00:00.000Z', 3, 1, 0, 'Oprati svo suđe.'),
(3, 'Odvesti sestru u grad', '2020-06-01T22:00:00.000Z', '2020-06-01T22:00:00.000Z', 1, 3, 0, 'Odvesti sestru u grad oko 18h i na povratku natrag skrenuti u pekaru po kruh.'),
(8, 'Dovršiti ovaj projekt', '2020-06-12T22:00:00.000Z', '2020-06-13T22:00:00.000Z', 2, 1, 1, 'Doraditi Postavke za korisnički račun preko modala te također dodati opciju odabira avatara kao slike profila. Urediti pregled_zadatka.html. Dodavanje komentara ostavljam za kraj jer ne bi trebali biti teški.'),
(11, 'Oguliti krumpire', '2020-06-16T22:00:00.000Z', '2020-06-16T22:00:00.000Z', 1, 3, 1, 'Oguliti cca 20 kom krumpira za ručak.'),
(28, 'Pokositi travu', '2020-06-18T15:37:35.757Z', '2020-06-18T15:37:35.757Z', 2, 1, 0, 'Pokositi travu iza kuće.'),
(29, 'Bla', '2020-06-18T15:40:32.812Z', '2020-06-18T15:40:32.812Z', 3, 2, 0, 'Bla Bla li'),
(54, 'Učiti baze', '2020-06-25T07:43:32.316Z', '2020-06-25T07:43:32.316Z', 1, 3, 1, 'Učiti za ispit iz baza podataka.'),
(55, 'Oprati auto', '2020-08-13T08:14:37.901Z', '2020-08-13T08:14:37.901Z', 3, 2, 1, 'Oprati auto izvana i iznutra.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zadatak`
--
ALTER TABLE `zadatak`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `zadatak`
--
ALTER TABLE `zadatak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
