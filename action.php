<?php
include "upravljanje_zadacima.php";
session_start();

$sPostData = file_get_contents("php://input");
$oPostData = json_decode($sPostData);

$sAction = $oPostData->action_id;


switch($sAction)
{

    // ------------------------------------------------------------------------ >> Provjera ulogiranog, Login, Registracija i Logout

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

    // ------------------------------------------------------------------------ >> Novi, Obrsi, Uredi, Dovrsi -> Zadatak

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

    case 'obrisi_zadatak':
        $ID = $oPostData->id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> ObrisiZadatak($ID);

        echo $status;
    break;

    case 'uredi_zadatak':
        $ID = $oPostData->id;
        $Naziv = $oPostData->naziv;
		$Datum_pocetka = $oPostData->datum_pocetka;
		$Datum_zavrsetka = $oPostData->datum_zavrsetka;
		$Izvrsitelj = $oPostData->izvrsitelj;
        $Opis = $oPostData->opis;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> UrediZadatak($ID, $Naziv, $Datum_pocetka, $Datum_zavrsetka, $Izvrsitelj, $Opis);

        echo $status;

    break;

    case 'dovrsi_zadatak':
        $ID = $oPostData->id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> DovrsiZadatak($ID);

        echo $status;
        
    break;

    case 'vrati_na_izvrsavanje':
        $ID = $oPostData->id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> VratiNaIzvrsavanje($ID);

        echo $status;
        
    break;

    // ------------------------------------------------------------------------ >> Postavke korisnickog profila

    case 'promjeni_avatara':
        $Avatar = $oPostData->avatar;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Avatar($Avatar, $UserID);

        echo $status;
    break;

    case 'promjeni_korisnicko_ime':
        $Korisnico_ime = $oPostData->korisnico_ime;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> KorisnickoIme($Korisnico_ime, $UserID);

        echo $status;
    break;

    case 'promjeni_ime':
        $Ime = $oPostData->ime;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Ime($Ime, $UserID);

        echo $status;
    break;
    
    case 'promjeni_prezime':
        $Prezime = $oPostData->prezime;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Prezime($Prezime, $UserID);

        echo $status;
    break;

    case 'promjeni_email':
        $Email = $oPostData->email;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Email($Email, $UserID);

        echo $status;
    break;

    case 'promjeni_tel':
        $Tel = $oPostData->tel;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Tel($Tel, $UserID);

        echo $status;
    break;

    case 'promjeni_bio':
        $Bio = $oPostData->bio;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Bio($Bio, $UserID);

        echo $status;
    break;

    case 'promjeni_prebivaliste':
        $Prebivaliste = $oPostData->prebivaliste;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Prebivaliste($Prebivaliste, $UserID);

        echo $status;
    break;

    case 'promjeni_datum_rodenja':
        $DatumRodenja = $oPostData->datum_rodenja;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> DatumRodenja($DatumRodenja, $UserID);

        echo $status;
    break;

    case 'promjeni_spol':
        $Spol = $oPostData->spol;
        $UserID = $oPostData->user_id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> Spol($Spol, $UserID);

        echo $status;
    break;

    // ------------------------------------------------------------------------ >> Komentiranje

    case 'novi_komentar':
        $Id_zadatka = $oPostData->id_zadatka;
        $Id_korisnika = $oPostData->id_korisnika;
        $Sadrzaj = $oPostData->sadrzaj;
        $Datum = $oPostData->datum;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> NoviKomentar($Id_zadatka, $Id_korisnika, $Sadrzaj, $Datum);

        echo $status;
    break;

    case 'uredi_komentar':
        $Opis = $oPostData->opis;
        $ID = $oPostData->id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> UrediKomentar($Opis, $ID);

        echo $status;
    break;

    case 'obrisi_komentar':
        $ID = $oPostData->id;
        
        $upravljanjeZadacima = new UpravljanjeZadacima();
        $status = $upravljanjeZadacima -> ObrisiKomentar($ID);

        echo $status;
    break;

}

?>