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
                $sQuery = "INSERT INTO korisnik (id, ime, prezime, lozinka, email, korisnicko_ime) VALUES (NULL, :Ime, :Prezime, :Lozinka, :Email, :KorisnickoIme)";
                $oData = array(
                    'Ime' => $Ime,
                    'Prezime' => $Prezime,
                    'Email' => $Email,
                    'Lozinka' => $Lozinka,
                    'KorisnickoIme' => $KorisnickoIme,
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
}


?>