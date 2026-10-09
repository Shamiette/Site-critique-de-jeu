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



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['adresse_mail'];
        $date_naiss = $_POST['date_de_naissance'];
        $image=$_POST['image_profil'];
        $annee_naiss=substr($date_naiss, 0, 4);

        $compte = getProfil($flux, $login);

        $annee_actuelle = date("Y");
        $age=$annee_actuelle-$annee_naiss;

        $login_esc = $flux->real_escape_string($login);
        $password_esc = $flux->real_escape_string($password);
        $nom_esc = $flux->real_escape_string($nom);
        $prenom_esc = $flux->real_escape_string($prenom);
        $mail_esc = $flux->real_escape_string($mail);

        if (!empty($compte[0])) {
            closeDB($flux);
            header("Location:../compte_invalide.php?status=existe");
        }elseif ($age<15){
            closeDB($flux);
            header("Location:../compte_invalide.php?status=age");
        }else {
            creationCompte($flux, $login_esc, $password_esc, $nom_esc, $prenom_esc, $mail_esc, $date_naiss, $image);
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['connected'] = true;
            closeDB($flux);
            header("Location:../index.php");

        }
    
    }
    
?>