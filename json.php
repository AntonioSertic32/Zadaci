<?php
header('Content-type: charset=ISO-8859-1');
include "connection.php";

$sJsonID = "";
$sUserID = "";

if (isset($_GET['json_id'])) {
    $sJsonID = $_GET['json_id'];
}
if (isset($_GET['korisnik_id'])) {
    $sUserID = $_GET['korisnik_id'];
}

$oJson = array();
switch ($sJsonID) {
    case 'dohvati_zadatke':
        /* SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelji, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelji=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id*/
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelji, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelji=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.izvrsitelji=$sUserID";
        
        $oRecord = $oConnection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oZadaci = new Zadatak(
                $oRow['id'],
                $oRow['naziv'],
                $oRow['datum_pocetka'],
                $oRow['datum_zavrsetka'],
                $oRow['izvrsitelji'],
                $oRow['kreator'],
                $oRow['stanje'],
                $oRow['opis']
            );
            array_push($oJson, $oZadaci);
        }
    break;

    case 'dohvati_korisnike':
        $sQuery = "SELECT * FROM korisnik";
        $oRecord = $oConnection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKorisnik = new Korisnik(
                $oRow['id'],
                $oRow['ime'],
                $oRow['prezime'],
                $oRow['lozinka'],
                $oRow['email'],
                $oRow['korisnicko_ime']
            );
            array_push($oJson, $oKorisnik);
        }
    break;

    case 'dohvati_komentare':
        $sQuery = "SELECT * FROM komentar";
        $oRecord = $oConnection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKomentar = new Komentar(
                $oRow['id'],
                $oRow['korisnik'],
                $oRow['opis'],
                $oRow['datum'],
                $oRow['zadatak']
            );
            array_push($oJson, $oKomentar);
        }
    break;
}
echo json_encode($oJson);
?>