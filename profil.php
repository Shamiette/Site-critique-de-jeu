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
    $login_regarde=$_GET['pseudo'];
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
                        echo '<li><a href="avis_compte.php" >Avis postés</a></li> ';
                        if ($login_regarde==$pseudo){
                            echo '<li><a href="profil.php?pseudo='.$_SESSION['login'].'" class="active">Mon compte: '.$_SESSION['login'].'</a></li> ';
                        }else{
                            echo '<li><a href="profil.php?pseudo='.$login_regarde.'" class="active">Compte de '.$login_regarde.'</a></li> ';
                            echo '<li><a href="profil.php?pseudo='.$_SESSION['login'].'">Mon compte: '.$_SESSION['login'].'</a></li> ';
                        }
                        echo '<li><a href="php/logout.php">Déconnexion </a></li> ';
                    } else {
                        echo '<li><a href="connexion.php">Connexion</a></li> ';
                        echo '<li><a href="creer_compte.php">Créer un compte</a></li> ';
                    }
                ?>
        </ul>
    </nav>

	<main>
        <?php  

            if (isset($_SESSION['login'])){
                $login_actuel=$_SESSION['login'];
            }else{
                $login_actuel='';
            }
            displayProfil($flux, $login_regarde, $login_actuel);

        ?>

    </main>

    <?php include ("static/footer.php"); ?>

</body >

<?php
    closeDB($flux);
?>

</html>