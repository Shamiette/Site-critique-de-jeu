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


        session_start();
        $flux = connectionDB();

        $compte=$_GET['compte'];
        $status=$_GET['status'];
        if ($status == 'supprime'){
            supprimeCompte($flux, $compte);
            closeDB($flux);
            if ($_SESSION['login']==$compte){
                header("Location:./logout.php");
            }
            header("Location:../index.php");
        }else{
            $niveau=$_POST['niveau'];
            modifieNiveau($flux, $compte, $niveau);
            header("Location:../profil.php?pseudo=".$compte."");

        }

    
    
?>