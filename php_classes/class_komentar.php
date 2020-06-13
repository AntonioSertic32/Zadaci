<?php

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