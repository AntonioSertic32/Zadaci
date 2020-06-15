-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2020 at 01:37 AM
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
(1, 'Mirko', 'Mirkić', 'a', 'a', 'Mirkec', 'doctor.png', '091785123', 'Mlad sam i zgodan. mmmmmm :) Obozavam programirati i pijem puno kave. volim cokoladu.', 'Dubrovnik', '4/5/2000', 'Muško'),
(2, 'Darko', 'Darkić', 'b', 'b', 'Darkec', 'doctor.png', '092741236', 'Samo Darkec ;)', 'Osijek', '9/10/1998', 'Muško'),
(3, 'Slavko', 'Slavkić', 'c', 'c', 'Slavkec', 'doctor.png', '093963258', '...', 'Zagreb', '6/6/1991', 'Muško');

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
(1, 'Spremiti sobu', '2/6/2020', '29/5/2020', 2, 1, 0, 'Spremiti robu u sobi.'),
(2, 'Oprat suđe', '2/6/2020', '29/5/2020', 3, 1, 0, 'Oprati svo suđe.'),
(3, 'Odvesti sestru u grad', '2/6/2020', '29/5/2020', 1, 3, 0, 'Odvesti sestru u grad oko 18h. Paziti na cestu hehe. I onda po kruha u pekaru.'),
(8, 'Isprogrmairati ovaj projekt', '13/6/2020', '16/62020', 1, 1, 0, 'Dovršiti ovaj projekt do idućeg utorka barem da sve funkcionalnosti rade. Pa onda poslije se mogu pozabaviti i izgledom.'),
(9, 'Časopisi', '13/6/2020', '13/6/2020', 1, 2, 0, 'Skuhati si kavu i pročitati sve časopise koji su ostali.');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `zadatak`
--
ALTER TABLE `zadatak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
