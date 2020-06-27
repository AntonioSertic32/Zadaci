<?php

class Komentar
{
    public $id = '';
    public $korisnik = '';
    public $opis = '';
    public $datum = '';
    public $zadatak = '';

    public function __construct($nId, $oUser, $sDescription, $sDate, $oTask)
    {
        $this->id = $nId;
        $this->korisnik = $oUser;
        $this->opis = $sDescription;
        $this->datum = $sDate;
        $this->zadatak = $oTask;
    }
}

?>