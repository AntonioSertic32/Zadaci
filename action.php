<?php
include "upravljanje_zadacima.php";
session_start();

$sPostData = file_get_contents("php://input");
$oPostData = json_decode($sPostData);

$sAction = $oPostData->action_id;


switch($sAction)
{
    case 'check_logged_in':
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> CheckLoggedIn();

        echo $status;

        break;

    case 'login':
        $Email = $oPostData->email;
        $Password = $oPostData->password;

        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Login($Email, $Password);

        echo $status;

        break;

    case 'registracija':

		$Ime = $oPostData->ime;
		$Prezime = $oPostData->prezime;
		$Email = $oPostData->email;
		$Lozinka = $oPostData->lozinka;
        $KorisnickoIme = $oPostData->korisnickoIme;
        
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Registracija($Ime, $Prezime, $Email, $Lozinka, $KorisnickoIme);

        echo $status;

    break;

	case 'logout':
		session_destroy();
    break;
    
    case 'novi_zadatak':

		$Naziv = $oPostData->naziv;
		$Datum_pocetka = $oPostData->datum_pocetka;
		$Datum_zavrsetka = $oPostData->datum_zavrsetka;
		$Izvrsitelj = $oPostData->izvrsitelj;
        $Kreator = $oPostData->kreator;
        $Opis = $oPostData->opis;
        
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> NoviZadatak($Naziv, $Datum_pocetka, $Datum_zavrsetka, $Izvrsitelj, $Kreator, $Opis);

        echo $status;

    break;

    case 'promjeni_spol':
        $Spol = $oPostData->spol;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Spol($Spol, $UserID);

        echo $status;
    break;

}

?>