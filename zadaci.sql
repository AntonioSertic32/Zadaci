CREATE DATABASE zadaci;

CREATE TABLE IF NOT EXISTS `zadaci`.`korisnik` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(45) NOT NULL,
  `prezime` VARCHAR(45) NOT NULL,
  `lozinka` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `korisnicko_ime` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `zadaci`.`zadatak` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(45) NOT NULL,
  `datum_pocetka` VARCHAR(50) NOT NULL,
  `datum_zavrsetka` VARCHAR(50) NOT NULL,
  `izvrsitelj` INT NOT NULL,
  `kreator` INT NOT NULL,
  `stanje` INT NOT NULL,
  `opis` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `zadaci`.`komentar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `korisnik` VARCHAR(50) NOT NULL,
  `opis` TEXT NOT NULL,
  `datum` VARCHAR(45) NOT NULL,
  `zadatak` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;
