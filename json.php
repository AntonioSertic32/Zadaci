<?php
header('Content-type: charset=ISO-8859-1');
include "upravljanje_zadacima.php";

$sJsonID = "";
$sUserID = "";

if (isset($_GET['json_id'])) {
    $sJsonID = $_GET['json_id'];
}
if (isset($_GET['korisnik_id'])) {
    $userId = $_GET['korisnik_id'];
}
if (isset($_GET['korisnik_username'])) {
    $username = $_GET['korisnik_username'];
}
if (isset($_GET['zadatak_id'])) {
    $zadatakID = $_GET['zadatak_id'];
}
if (isset($_GET['tip_pretrage'])) {
    $tip_pretrage = $_GET['tip_pretrage'];
}



switch ($sJsonID) {

    // ---------------------------------------------------------------------------------- >> Dohvacanje zadataka

    case 'dohvati_moje_zadatke':
        
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiMojeZadatke();
        echo $upravljanjeZadacima -> IspisiZadatke();

    break;

    case 'dohvati_kreirane_zadatke':
        
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiKreiraneZadatke();
        echo $upravljanjeZadacima -> IspisiZadatke();

    break;
    
    case 'dohvati_dovrsene_zadatke':
        
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiDovrseneZadatke();
        echo $upravljanjeZadacima -> IspisiZadatke();

    break;

    case 'dohvati_dovrsene_kreirane_zadatke':
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiDovrseneKreiraneZadatke();
        echo $upravljanjeZadacima -> IspisiZadatke();
    break;

    // Dohvacanje zasebnih zadataka
    
    case 'dohvati_kreirani_zadatak':
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiKreiraniZadatak($zadatakID);
        
        echo $upravljanjeZadacima -> IspisiZadatak();
        
    break;

    case 'dohvati_zadatak':
        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiZadatak($zadatakID);
        echo $upravljanjeZadacima -> IspisiZadatak();
        
    break;

    // ---------------------------------------------------------------------------------- >> Dohvacanje korisnika

    case 'dohvati_korisnike':

        $upravljanjeZadacima = new UpravljanjeZadacima();
        $upravljanjeZadacima -> DohvatiKorisnike();
        echo $upravljanjeZadacima -> IspisiKorisnike();
        
    break;

    case 'dohvati_korisnika':

        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiKorisnika();
        echo $upravljanjeZadacima -> IspisiKorisnika();
        
    break;

    case 'dohvati_id_drugog_korisnika':

        $upravljanjeZadacima = new UpravljanjeZadacima();
        $upravljanjeZadacima -> DohvatiDrugogKorisnika($username);
        echo $upravljanjeZadacima -> IspisiKorisnika();
        
    break;

    // ---------------------------------------------------------------------------------- >> Dohvacanje komentara

    case 'dohvati_komentare':

        $upravljanjeZadacima = new UpravljanjeZadacima($userId);
        $upravljanjeZadacima -> DohvatiKomentare($zadatakID);
        echo $upravljanjeZadacima -> IspisiKomentare();
        
    break;

}
?>