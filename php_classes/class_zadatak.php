<?php

class Zadatak
{
    public $id = '';
    public $naziv = '';
    public $datum_pocetka = '';
    public $datum_zavrsetka = '';
    public $izvrsitelj = '';
    public $kreator = '';
    public $stanje = '';
    public $opis = '';

    public function __construct($nId, $sName, $sStartDate, $sFinishDate, $sAssigned, $sCreator, $fProgress, $sDescription)
    {
        $this->id = $nId;
        $this->naziv = $sName;
        $this->datum_pocetka = $sStartDate;
        $this->datum_zavrsetka = $sFinishDate;
        $this->izvrsitelj = $sAssigned;
        $this->kreator = $sCreator;
        $this->stanje = $fProgress;
	    $this->opis = $sDescription;
    }
}

?>