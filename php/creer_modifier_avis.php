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

    if (isset($_POST['titre'])) {
        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $note = $_POST['note'];
    }

        session_start();
        $flux = connectionDB();
        $jeu=$_GET['jeu'];
        $status=$_GET['status'];
        if($status=="supprime_autre"){
            $login=$_GET['pseudo'];
        }else{
            $login=$_SESSION['login'];
        }
        

        if ($status=='creation'){
            creerAvis($flux, $login, $titre, $texte, $note, $jeu);
            closeDB($flux);
            header("Location:../article.php?numero=" . $jeu);
        }elseif ($status=='modification'){
            modifierAvis($flux, $login, $titre, $texte, $note, $jeu);
            closeDB($flux);
            header("Location:../avis_compte.php");
        }elseif ($status=='supprime'){
            supprimeAvis($flux, $login, $jeu);
            closeDB($flux);
            header("Location:../avis_compte.php");
        }elseif ($status=='supprime_autre'){
            supprimeAvis($flux, $login, $jeu);
            closeDB($flux);
            header("Location:../article.php?numero=" . $jeu);
        }
    
    
?>