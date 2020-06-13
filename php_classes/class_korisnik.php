<?php

class Osoba {
    public $ime = '';
    public $prezime = '';
}

class Korisnik extends Osoba
{
    public $id = '';
    //public $ime = '';
    //public $prezime = '';
    public $lozinka = '';
    public $email = '';
    public $korisnicko_ime = '';
    //Sintaksta nasljeđivanja ##!!!
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

?>