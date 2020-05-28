<?php
include "connection.php";
session_start();

$sPostData = file_get_contents("php://input");
$oPostData = json_decode($sPostData);

$sAction = $oPostData->action_id;

switch($sAction)
{
    case 'check_logged_in':
        if (isset($_SESSION['user_id'])) {

            echo json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['user_id'],
            ));
        } else {
            echo json_encode(array(
                "status" => 0,
            ));
        }
        break;

    case 'login':
        $Email = $oPostData->email;
        $Password = $oPostData->password;

        $sQuery = "SELECT * FROM korisnik WHERE email='$Email' AND lozinka='$Password'";
        $oRecord = $oConnection->query($sQuery);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();

        if ($count > 0) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['korisnicko_ime'] = $row['korisnicko_ime'];
            echo json_encode(array(
                "status" => 1,
                "user_id" => $_SESSION['user_id'],
            ));
        } else {
            echo json_encode(array(
                "status" => 0,
            ));
        }
        break;

    case 'registracija':

		$Ime = $oPostData->ime;
		$Prezime = $oPostData->prezime;
		$Email = $oPostData->email;
		$Lozinka = $oPostData->lozinka;
        $KorisnickoIme = $oPostData->korisnickoIme;
        
        $sQueryOne = "SELECT email FROM korisnik WHERE email='$Email'"; // OR korisnicko_ime='$KorisnickoIme'";
        $oRecord = $oConnection->query($sQueryOne);
        $row = $oRecord->fetch();
        $count = $oRecord->rowCount();
        if ($count > 0) {
            echo "Već postoji korisnik s tim Email-om!";
        }
        else {
            $sQueryTwo= "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime='$KorisnickoIme'";
            $oRecord = $oConnection->query($sQueryTwo);
            $row = $oRecord->fetch();
            $count = $oRecord->rowCount();
            if ($count > 0) {
                echo "Već postoji korisnik s tim Korisničkim imenom!";
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
                    $oStatement = $oConnection->prepare($sQuery);
                    $oStatement->execute($oData);
                    echo 1;
                } catch (PDOException $error) {
                    echo $error;
                    echo 0;
                }
            }

        }

		
    break;

	case 'logout':
		session_destroy();
	break;

}

?>