<?php
header('Content-type: charset=ISO-8859-1');
include "connection.php";

$sJsonID = "";

if (isset($_GET['json_id'])) {
    $sJsonID = $_GET['json_id'];
}

$oJson = array();
switch ($sJsonID) {
    case 'dohvati_zadatke':
        $sQuery = "SELECT * FROM zadatak ";
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
}
echo json_encode($oJson);
?>