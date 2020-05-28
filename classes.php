<?php

class Configuration
{
    public $host = 'localhost';
    public $dbName = 'zadaci';
    public $username = 'root';
    public $password = '';
};

class Osoba {
    public $ime = '';
    public $prezime = '';
}

class Korisnik extends Osoba
{
    public $id = '';
    public $ime = '';
    public $prezime = '';
    public $lozinka = '';
    public $email = '';
    public $korisnicko_ime = '';

    public function __construct($nId, $sName, $sSurname, $sPassword, $sEmail, $sUsername)
    {
        $this->id = $nId;
        $this->ime = $sName;
        $this->prezime = $sSurname;
        $this->lozinka = $sPassword;
        $this->email = $sEmail;
        $this->korisnicko_ime = $sUsername;
    }
}

class Zadatak
{
    public $id = '';
    public $naziv = '';
    public $datum_pocetka = '';
    public $datum_zavrsetka = '';
    public $izvrsitelji = '';
    public $kreator = '';
    public $stanje = '';
    public $opis = '';

    public function __construct($nId, $sName, $sStartDate, $sFinishDate, $sAssigned, $sCreator, $fProgress, $sDescription)
    {
        $this->id = $nId;
        $this->naziv = $sName;
        $this->datum_pocetka = $sStartDate;
        $this->datum_zavrsetka = $sFinishDate;
        $this->izvrsitelji = $sAssigned;
        $this->kreator = $sCreator;
        $this->stanje = $fProgress;
	$this->opis = $sDescription;
    }
}

class Komentar
{
    public $id = '';
    public $korisnik = '';
    public $opis = '';
    public $datum_zavrsetka = '';
    public $zadatak = '';

    public function __construct($nId, $oUser, $sDescription, $sDate, $oTask)
    {
        $this->id = $nId;
        $this->korisnik = $oUser;
        $this->opis = $sDescription;
        $this->datum_zavrsetka = $sDate;
        $this->zadatak = $oTask;
    }
}

?>