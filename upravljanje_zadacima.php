<?php

include "php_classes/class_korisnik.php";
include "php_classes/class_zadatak.php";
include "php_classes/class_komentar.php";

class UpravljanjeZadacima {

    //Konekcija
    public $host = 'localhost';
    public $dbName = 'zadaci';
    public $username = 'root';
    public $password = '';

    public $userId = '';
    public $connection = NULL;

    public $korisnici = array();
    public $zadaci = array();
    public $komentari = array();

    public $zadatak = array();

    public function __construct($user_id = NULL)
    {
        try
        {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
        }
        catch (PDOException $pe)
        {
            die("Could not connect to the database $this->dbName :" . $pe->getMessage());
        }
        $this->userId = $user_id;
    }

    public function CheckLoggedIn() {
        if (isset($_SESSION['user_id'])) {

            return json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['user_id'],
                "user_username" => $_SESSION['user_username']
            ));
        } else {
            return json_encode(array(
                "status" => 0,
            ));
        }
    }

    public function Login($email, $pass)
    {
        $Email = $email;
        $Password = $pass;

        $sQuery = "SELECT * FROM korisnik WHERE email='$Email' AND lozinka='$Password'";

            $oRecord =  $this->connection->query($sQuery);
            
        $oRecord =  $this->connection->query($sQuery);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();

        if ($count > 0) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_username'] = $row['korisnicko_ime'];
            return json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['user_id'],
                "user_username" => $_SESSION['user_username']
            ));
        } else {
            return json_encode(array(
                "status" => 0,
            ));
        }
    }

    public function Registracija($Ime, $Prezime, $Email, $Lozinka, $KorisnickoIme)
    {
        $sQueryOne = "SELECT email FROM korisnik WHERE email='$Email'";
        $oRecord = $this->connection->query($sQueryOne);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();
        if ($count > 0) {
            return "Već postoji korisnik s tim Email-om!";
        }
        else {
            $sQueryTwo= "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime='$KorisnickoIme'";
            $oRecord = $this->connection->query($sQueryTwo);
            $row = $oRecord->fetch();
            $count = $oRecord->rowCount();
            if ($count > 0) {
                return "Već postoji korisnik s tim Korisničkim imenom!";
            }
            else {
                $sQuery = "INSERT INTO korisnik (id, ime, prezime, lozinka, email, korisnicko_ime, slika) VALUES (NULL, :Ime, :Prezime, :Lozinka, :Email, :KorisnickoIme, :Slika)";
                $oData = array(
                    'Ime' => $Ime,
                    'Prezime' => $Prezime,
                    'Email' => $Email,
                    'Lozinka' => $Lozinka,
                    'KorisnickoIme' => $KorisnickoIme,
                    'Slika' => "default.png"
                );
                try
                {
                    $oStatement = $this->connection->prepare($sQuery);
                    $oStatement->execute($oData);
                    return 1;
                } catch (PDOException $error) {
                    return $error;
                }
            }
        }
    }

    // Novi zadatak
    public function NoviZadatak($Naziv, $Datum_pocetka, $Datum_zavrsetka, $Izvrsitelj, $Kreator, $Opis)
    {
        $sQuery = "INSERT INTO zadatak (id, naziv, datum_pocetka, datum_zavrsetka, izvrsitelj, kreator, stanje, opis) VALUES (NULL, :Naziv, :Datum_pocetka, :Datum_zavrsetka, :Izvrsitelj, :Kreator, :Stanje, :Opis)";
                $oData = array(
                    'Naziv' => $Naziv,
                    'Datum_pocetka' => $Datum_pocetka,
                    'Datum_zavrsetka' => $Datum_zavrsetka,
                    'Izvrsitelj' => $Izvrsitelj,
                    'Kreator' => $Kreator,
                    'Stanje' => 0,
                    'Opis' => $Opis,
                );
                try
                {
                    $oStatement = $this->connection->prepare($sQuery);
                    $oStatement->execute($oData);
                    return 1;
                } catch (PDOException $error) {
                    return $error;
                }
    }

    //Dohvacanje korisnika
    
    public function DohvatiKorisnike()
    {
        $sQuery = "SELECT * FROM korisnik";
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKorisnik = new Korisnik(
                $oRow['id'],
                $oRow['ime'],
                $oRow['prezime'],
                $oRow['lozinka'],
                $oRow['email'],
                $oRow['korisnicko_ime']
            );
            array_push($this->korisnici, $oKorisnik);
        }
    }
    public function IspisiKorisnike()
    {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->korisnici);
    }
    

    //Dohvacanje mojih zadataka
    public function DohvatiMojeZadatke()
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.izvrsitelj=$this->userId";
        
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oZadaci = new Zadatak(
                $oRow['id'],
                $oRow['naziv'],
                $oRow['datum_pocetka'],
                $oRow['datum_zavrsetka'],
                $oRow['izvrsitelj'],
                $oRow['kreator'],
                $oRow['stanje'],
                $oRow['opis']
            );
            array_push($this->zadaci, $oZadaci);
        }
    }

    //Dohvacanje kreiranih zadataka
    public function DohvatiKreiraneZadatke()
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.kreator=$this->userId";
        
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oZadaci = new Zadatak(
                $oRow['id'],
                $oRow['naziv'],
                $oRow['datum_pocetka'],
                $oRow['datum_zavrsetka'],
                $oRow['izvrsitelj'],
                $oRow['kreator'],
                $oRow['stanje'],
                $oRow['opis']
            );
            array_push($this->zadaci, $oZadaci);
        }
    }

    //Dohvacanje komentara
    public function DohvatiKomentare()
    {
        $sQuery = "SELECT * FROM komentar";
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKomentar = new Komentar(
                $oRow['id'],
                $oRow['korisnik'],
                $oRow['opis'],
                $oRow['datum'],
                $oRow['zadatak']
            );
            array_push($this->komentari, $oKomentar);
        }
    }
    public function IspisiZadatke()
    {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->zadaci);
    }

    // Dohvacanje korisnika
    public function DohvatiKorisnika() {
        
        $sQuery = "SELECT * FROM korisnik WHERE korisnik.id = $this->userId";
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKorisnik = new Korisnik(
                $oRow['id'],
                $oRow['ime'],
                $oRow['prezime'],
                $oRow['lozinka'],
                $oRow['email'],
                $oRow['korisnicko_ime'],
                $oRow['slika'],
                $oRow['tel'],
                $oRow['bio'],
                $oRow['prebivaliste'],
                $oRow['datum_rodenja'],
                $oRow['spol']
            );
            array_push($this->korisnici, $oKorisnik);
        }
    }
    public function IspisiKorisnika() {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->korisnici);
    }

    public function DohvatiDrugogKorisnika($username) {
        
        $sQuery = "SELECT * FROM korisnik WHERE korisnicko_ime = '$username'";
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oKorisnik = new Korisnik(
                $oRow['id'],
                $oRow['ime'],
                $oRow['prezime'],
                $oRow['lozinka'],
                $oRow['email'],
                $oRow['korisnicko_ime'],
                $oRow['slika'],
                $oRow['tel'],
                $oRow['bio'],
                $oRow['prebivaliste'],
                $oRow['datum_rodenja'],
                $oRow['spol']
            );
            array_push($this->korisnici, $oKorisnik);
        }
    }

    public function Spol($Spol, $UserID) {
        if($Spol == " ") {
            
            $sQuery = "UPDATE korisnik SET spol = null WHERE id = $UserID";
        }else {

            $sQuery = "UPDATE korisnik SET spol = '$Spol' WHERE id = $UserID";
        }
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    public function ObrisiZadatak($ID) {
        $sQuery = "DELETE FROM zadatak WHERE id = '$ID'";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    public function UrediZadatak($ID, $Naziv, $Datum_pocetka, $Datum_zavrsetka, $Izvrsitelj, $Opis) {
        $sQuery = "UPDATE zadatak SET naziv = '$Naziv', datum_pocetka = '$Datum_pocetka', datum_zavrsetka = '$Datum_zavrsetka', izvrsitelj = '$Izvrsitelj', opis = '$Opis' WHERE id = $ID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    public function DohvatiKreiraniZadatak($zadatakID)
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.kreator=$this->userId AND zadatak.id= $zadatakID";
        
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oZadaci = new Zadatak(
                $oRow['id'],
                $oRow['naziv'],
                $oRow['datum_pocetka'],
                $oRow['datum_zavrsetka'],
                $oRow['izvrsitelj'],
                $oRow['kreator'],
                $oRow['stanje'],
                $oRow['opis']
            );
            array_push($this->zadatak, $oZadaci);
        }
    }
    public function DohvatiZadatak($zadatakID)
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.izvrsitelj=$this->userId AND zadatak.id= $zadatakID";
        
        $oRecord = $this->connection->query($sQuery);
        while ($oRow = $oRecord->fetch(PDO::FETCH_BOTH)) {
            $oZadaci = new Zadatak(
                $oRow['id'],
                $oRow['naziv'],
                $oRow['datum_pocetka'],
                $oRow['datum_zavrsetka'],
                $oRow['izvrsitelj'],
                $oRow['kreator'],
                $oRow['stanje'],
                $oRow['opis']
            );
            array_push($this->zadatak, $oZadaci);
        }
    }
    public function IspisiZadatak() {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->zadatak);
    }

    public function DovrsiZadatak($ID) {
        $sQuery = "UPDATE zadatak SET stanje = 1 WHERE id = $ID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }
}


?>