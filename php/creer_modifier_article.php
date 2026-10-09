<?php
//affichage des erreurs côté PHP et côté MYSQLI
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Import du site - A completer
require_once("../includes/config-bdd.php");
require_once("../includes/constantes.php");      //constantes du site
include("../php/functions-DB.php");
include("../php/functions_query.php");
include("../php/functions_structure.php");

    session_start();
    $flux = connectionDB();

    $login=$_SESSION['login'];
    $status=$_GET['status'];
    

    if ($status=='creation'){
        $jeu = $_POST['jeu'];
        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $note = $_POST['note'];
        $categories = $_POST['categorie'];
        $supports = $_POST['support'];
        foreach ($categories as $categorie){
            $cat=getIdCategorie($flux, $categorie);
            print_r($cat);
            associeCategorie($flux, $jeu, $cat[0]['id_categorie']);
        }
        foreach ($supports as $support){
            $sup=getIdSupport($flux, $support);
            print_r($sup);
            associeSupport($flux, $jeu, $sup[0]['id_support']);
        }
        creerArticle($flux, $login, $titre, $texte, $note, $jeu);
        closeDB($flux);
        header("Location:../article.php?numero=" . $jeu);
    }elseif ($status=='modification'){
        $titre = $_POST['titre'];
        $texte = $_POST['texte'];
        $note = $_POST['note'];
        $pseudo = $_GET['pseudo'];
        $jeu= $_GET['jeu'];
        modifierArticle($flux, $login, $titre, $texte, $note, $jeu);
        closeDB($flux);
        header("Location:../articles_compte.php" . $jeu);
    }elseif ($status=='supprime'){
        $pseudo = $_GET['pseudo'];
        $jeu= $_GET['jeu'];
        supprimeArticle($flux, $pseudo, $jeu);
        closeDB($flux);
        if ($pseudo==$login){
            header("Location:../articles_compte.php?");
        }else{
            header("Location:../index.php?numero=" . $jeu);
        }
    }


    
    