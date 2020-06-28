<?php

class Osoba {
    public $ime = '';
    public $prezime = '';

    public $slika ='';
    public $tel ='';
    public $bio ='';
    public $prebivaliste ='';
    public $datum_rodenja ='';
    public $spol ='';
}

class Korisnik extends Osoba
{
    public $id = '';
    public $lozinka = '';
    public $email = '';
    public $korisnicko_ime = '';

    public function __construct($nId, $sName, $sSurname, $sPassword, $sEmail, $sUsername, $sSlika = NULL, $sTel = NULL, $sBio = NULL, $sPrebivaliste = NULL, $sDatum_rodenja = NULL, $sSpol = NULL)
    {
        $this->id = $nId;
        $this->ime = $sName;
        $this->prezime = $sSurname;
        $this->lozinka = $sPassword;
        $this->email = $sEmail;
        $this->korisnicko_ime = $sUsername;

        $this->slika = $sSlika;
        $this->tel = $sTel;
        $this->bio = $sBio;
        $this->prebivaliste = $sPrebivaliste;
        $this->datum_rodenja = $sDatum_rodenja;
        $this->spol = $sSpol;
    }
}

?>