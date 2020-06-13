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

switch ($sJsonID) {
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

    case 'dohvati_korisnike':

        $upravljanjeZadacima = new UpravljanjeZadacima();
        $upravljanjeZadacima -> DohvatiKorisnike();
        
    break;

    case 'dohvati_komentare':

        $upravljanjeZadacima = new UpravljanjeZadacima();
        $upravljanjeZadacima -> DohvatiKomentare();
        
    break;
}
?>