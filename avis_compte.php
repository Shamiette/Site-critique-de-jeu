<?php

//affichage des erreurs côté PHP et côté MYSQLI
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Import du site - A completer
require_once("./includes/config-bdd.php");
require_once("./includes/constantes.php");      //constantes du site
include("./php/functions-DB.php");
include("./php/functions_query.php");
include("./php/functions_structure.php");

?>
<!DOCTYPE html>
<html lang="fr">

<?php include ("static/head.php"); ?>

<?php
    $flux = connectionDB();
    session_start();
?>

<body>
    <?php include ("static/header.php"); ?>
    
    <nav>
        <ul>
            <li><a href="index.php"> Accueil </a></li>
                <?php
                    if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
                        $pseudo=$_SESSION['login'];
                        $compte=getProfil($flux, $pseudo);

                        if ($compte[0]['niveau']=='rédacteur' || $compte[0]['niveau']=='admin'){
                            echo '<li><a href="ecrire_article.php">Écrire un article</a></li>';
                            echo '<li><a href="articles_compte.php">Articles postés</a></li>';
                        }
                        echo '<li><a href="avis_compte.php" class="active">Avis postés</a></li> ';
                        echo '<li><a href="profil.php?pseudo='.$_SESSION['login'].'">Mon compte: '.$_SESSION['login'].'</a></li> ';
                        echo '<li><a href="php/logout.php">Déconnexion </a></li> ';
                    } else {
                        echo '<li><a href="connexion.php">Connexion</a></li> ';
                    }
                ?>
        </ul>
    </nav>

	<main>
        <?php  
        if (isset($_SESSION['connected'])){
            $login=$_SESSION['login'];
            displayAvisCompte($flux, $login);
        }else{
            header("Location:./index.php");
        }
        ?>

    </main>

    <?php include ("static/footer.php"); ?>

</body >

<?php
    closeDB($flux);
?>

</html>