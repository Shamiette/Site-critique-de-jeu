<!DOCTYPE html>
<html lang="fr">

<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once("./includes/config-bdd.php");
require_once("./includes/constantes.php");
include ("static/head.php");
include("./php/functions-DB.php");
include("./php/functions_query.php");
include("./php/functions_structure.php");
?>

<body>
    <?php include ("static/header.php"); ?>
    
    <nav>
        <ul>
            <li><a href="index.php"> Accueil </a></li>
            <li>
                <?php
                    if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
                        $pseudo=$_SESSION['login'];
                        $compte=getProfil($flux, $pseudo);
                        if ($compte[0]['niveau']=='rédacteur' || $compte[0]['niveau']=='admin'){
                            echo '<li><a href="ecrire_article.php">Écrire un article</a></li>';
                            echo '<li><a href="articles_compte.php">Articles postés</a></li>';
                        }
                        echo '<a href="avis_compte.php">Avis postés</a>';
                        echo '<a href="php/logout.php">Déconnexion</a>';
                    } else {
                        echo '<a href="connexion.php">Connexion</a>';
                        echo '<li><a href="creer_compte.php" class="active">Créer un compte</a></li> ';
                    }
                ?>
            </li>
        </ul>
    </nav>

	<main>
    <?php
    $flux = connectionDB(); 
    displayFormCreaCompte($flux) ?>
    </main>

    <?php include ("static/footer.php"); ?>

</body>

</html>