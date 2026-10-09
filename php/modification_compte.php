<?php
    //affichage des erreurs côté PHP et côté MYSQLI
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //Import du site - A completer
    require_once("../includes/config-bdd.php");
    require_once("../includes/constantes.php");
    include("functions-DB.php");
    include("functions_query.php");

    $flux = connectionDB();

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['adresse_mail'];
        $date_naiss = $_POST['date_de_naissance'];
        $image=$_POST['image_profil'];
        $login=$_SESSION['login'];
        $annee_naiss=substr($date_naiss, 0, 4);
        $compte=getProfil($flux, $login);

        $annee_actuelle = date("Y");
        $age=$annee_actuelle-$annee_naiss;

        if ($age<15){
            closeDB($flux);
            header("Location:../compte_invalide.php?status=age");
        }else {
            modificationCompte($flux, $login, $password, $nom, $prenom, $mail, $date_naiss, $image, $compte[0]['date_crea_compte'], $compte[0]['date_der_co'], $compte[0]['niveau']);
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['connected'] = true;
            closeDB($flux);
            header('Location:../profil.php?pseudo='. $login.'');

        }
    
    }
    
?>