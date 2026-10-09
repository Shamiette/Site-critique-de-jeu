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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $flux = connectionDB();

        $compte = getCompte($flux, $login, $password);

        if (empty($compte[0])) {
            closeDB($flux);
            header("Location:../connexion.php");
        }
        else {
            session_start();
            $_SESSION['login'] = $compte[0]['pseudo'];
            $_SESSION['password'] = $compte[0]['mdp'];
            $_SESSION['connected'] = true;

            updateDateConnexion($flux, $compte[0]['pseudo']);

            closeDB($flux);
            header("Location:../index.php");
        }
    
    }
    
?>