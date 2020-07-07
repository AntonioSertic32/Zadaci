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

    // ------------------------------------------------------------------------ >>
    // ------------------------------------------------------------------------ >> Provjera ulogiranog, Login i Registracija
    // ------------------------------------------------------------------------ >>

    // Provjera ulogiranog
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

    // Login
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

    // Registracija
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

    // ------------------------------------------------------------------------ >>
    // ------------------------------------------------------------------------ >> Novi, Obrsi, Uredi, Dovrsi -> Zadatak
    // ------------------------------------------------------------------------ >>

    // Novi zadatak
    public function NoviZadatak($Naziv, $Datum_pocetka, $Datum_zavrsetka, $Izvrsitelj, $Kreator, $Opis)
    {
        //SELECT * FROM zadatak WHERE naziv = "SPremiti sobu" AND izvrsitelj = 1
        $sQueryOne = "SELECT * FROM zadatak WHERE naziv='$Naziv' AND izvrsitelj = '$Izvrsitelj' AND stanje = 0";
        $oRecord = $this->connection->query($sQueryOne);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();
        if ($count > 0) {
            return "Već postoji taj zadatak za tog korisnika!";
        }
        else {
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
    }
    
    // Obrisi zadatak
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

    // Uredi zadatak
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
    
    // Dovrsi zadatak
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

    // Vrati na izvrsavanje
    public function VratiNaIzvrsavanje($ID) {
        $sQuery = "UPDATE zadatak SET stanje = 0 WHERE id = $ID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }
    
    // -------------------------------------------------------------------------------------- >>
    // -------------------------------------------------------------------------------------- >> Dohvacanje zadataka
    // -------------------------------------------------------------------------------------- >>
    
    //Dohvacanje mojih zadataka
    public function DohvatiMojeZadatke()
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.izvrsitelj=$this->userId AND zadatak.stanje=0";
        
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
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.kreator=$this->userId AND zadatak.stanje=0";
        
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

    //Dohvacanje dovrsenih zadataka
    public function DohvatiDovrseneZadatke()
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.izvrsitelj=$this->userId AND zadatak.stanje=1";
        
        
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

    //Dohvacanje dovrsenih kreiranih zadataka
    public function DohvatiDovrseneKreiraneZadatke()
    {
        $sQuery = "SELECT zadatak.id, zadatak.naziv, zadatak.datum_pocetka, zadatak.datum_zavrsetka, k1.korisnicko_ime as izvrsitelj, k2.korisnicko_ime as kreator, zadatak.stanje, zadatak.opis FROM zadatak LEFT JOIN korisnik k1 ON zadatak.izvrsitelj=k1.id LEFT JOIN korisnik k2 ON zadatak.kreator=k2.id WHERE zadatak.kreator=$this->userId AND zadatak.stanje=1";
        
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
    
    public function IspisiZadatke()
    {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->zadaci);
    }
    
    //Dohvacanje kreiranog zadatka
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

    //Dohvacanje mojeg zadatka
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

    // -------------------------------------------------------------------------------------- >>
    // -------------------------------------------------------------------------------------- >> Dohvacanje Korisnika
    // -------------------------------------------------------------------------------------- >>

    //Dohvacanje svih korisnika
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

    // Dohvacanje korisnika preko id-a
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

    // Dohvacanje korisnika preko username-a
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
    
    // -------------------------------------------------------------------------------------- >>
    // -------------------------------------------------------------------------------------- >> Komentiranje
    // -------------------------------------------------------------------------------------- >>

    // Novi komentar
    public function NoviKomentar($Id_zadatka, $Id_korisnika, $Sadrzaj, $Datum)
    {
        $sQuery = "INSERT INTO komentar (id, korisnik, opis, datum, zadatak) VALUES (NULL, :Id_kor, :Sad, :Dat, :Id_zad)";
        $oData = array(
            'Id_zad' => $Id_zadatka,
            'Id_kor' => $Id_korisnika,
            'Sad' => $Sadrzaj,
            'Dat' => $Datum
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

    //Dohvacanje komentara
    public function DohvatiKomentare($zadatakID)
    {
        $sQuery = "SELECT komentar.id, k1.korisnicko_ime as korisnik, komentar.opis, komentar.datum, komentar.zadatak FROM komentar LEFT JOIN korisnik k1 ON komentar.korisnik=k1.id WHERE zadatak = $zadatakID";
        
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
    public function IspisiKomentare()
    {
        header('Content-type: charset=ISO-8859-1');
        return json_encode($this->komentari);
    }

    // Uredivanje komentara
    public function UrediKomentar($Opis, $ID) {
        $sQuery = "UPDATE komentar SET opis = '$Opis' WHERE id = $ID";
        
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }          
    }

    // Brisanje komentara
    public function ObrisiKomentar($ID) {
        $sQuery = "DELETE FROM komentar WHERE id = $ID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }
    
    // -------------------------------------------------------------------------------------- >>
    // -------------------------------------------------------------------------------------- >> Postavke
    // -------------------------------------------------------------------------------------- >>

    // Avatar
    public function Avatar($Avatar, $UserID) {
        
        $sQuery = "UPDATE korisnik SET slika = '$Avatar' WHERE id = $UserID";

        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    // Korisnicko ime
    public function KorisnickoIme($Korisnico_ime, $UserID) {
        $sQueryProvjera= "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime='$Korisnico_ime'";
        $oRecord = $this->connection->query($sQueryProvjera);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();
        if ($count > 0) {
            return "Već postoji korisnik s tim Korisničkim imenom!";
        }
        else {
            $sQuery = "UPDATE korisnik SET korisnicko_ime = '$Korisnico_ime' WHERE id = $UserID";
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

    // Ime
    public function Ime($Ime, $UserID) {
        $sQuery = "UPDATE korisnik SET ime = '$Ime' WHERE id = $UserID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }        
    }

    public function Prezime($Prezime, $UserID) {
        $sQuery = "UPDATE korisnik SET prezime = '$Prezime' WHERE id = $UserID";
        try
        {
            $stmt =$this->connection->prepare($sQuery);
            $stmt->execute();
            return 1;
        } catch (PDOException $error) {
            return $error->getMessage();
        }          
    }

    // Prezime
    public function Email($Email, $UserID) {
        $sQueryOne = "SELECT email FROM korisnik WHERE email='$Email'";
        $oRecord = $this->connection->query($sQueryOne);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();
        if ($count > 0) {
            return "Već postoji korisnik s tim Email-om!";
        }
        else {
            $sQuery = "UPDATE korisnik SET email = '$Email' WHERE id = $UserID";
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

    // Telefon
    public function Tel($Tel, $UserID) {
        if($Tel == "") {
            
            $sQuery = "UPDATE korisnik SET tel = null WHERE id = $UserID";
        }else {

            $sQuery = "UPDATE korisnik SET tel = '$Tel' WHERE id = $UserID";
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

    // Biografija
    public function Bio($Bio, $UserID) {
        if($Bio == "") {
            
            $sQuery = "UPDATE korisnik SET bio = null WHERE id = $UserID";
        }else {

            $sQuery = "UPDATE korisnik SET bio = '$Bio' WHERE id = $UserID";
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

    // Prebivaliste
    public function Prebivaliste($Prebivaliste, $UserID) {
        if($Prebivaliste == "") {
            
            $sQuery = "UPDATE korisnik SET prebivaliste = null WHERE id = $UserID";
        }else {

            $sQuery = "UPDATE korisnik SET prebivaliste = '$Prebivaliste' WHERE id = $UserID";
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

    // Datum rodenja
    public function DatumRodenja($DatumRodenja, $UserID) {
        if($DatumRodenja == "obrisi") {
            
            $sQuery = "UPDATE korisnik SET datum_rodenja = null WHERE id = $UserID";
        }else {

            $sQuery = "UPDATE korisnik SET datum_rodenja = '$DatumRodenja' WHERE id = $UserID";
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

    //Spol
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
}


?>