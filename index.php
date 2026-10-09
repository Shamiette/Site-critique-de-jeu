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
            <li><a href="index.php" class="active"> Accueil </a></li>
                <?php
                    if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
                        $pseudo=$_SESSION['login'];
                        $compte=getProfil($flux, $pseudo);
                        if ($compte[0]['niveau']=='rédacteur' || $compte[0]['niveau']=='admin'){
                            echo '<li><a href="ecrire_article.php">Écrire un article</a></li>';
                            echo '<li><a href="articles_compte.php">Articles postés</a></li>';
                        }
                        echo '<li><a href="avis_compte.php">Avis postés</a></li> ';
                        echo '<li><a href="profil.php?pseudo='.$_SESSION['login'].'">Mon compte: '.$_SESSION['login'].'</a></li> ';
                        echo '<li><a href="php/logout.php">Déconnexion </a></li> ';
                    } else {
                        echo '<li><a href="connexion.php">Connexion</a></li> ';
                        echo '<li><a href="creer_compte.php">Créer un compte</a></li> ';
                    }
                ?>
            </li>
        </ul>
    </nav>

	<main>
        <div class="barre-recherche">
            <form method="GET" action="index.php">
                <input class="text-recherche right" type="text" name="recherche" placeholder="Rechercher un jeu" value="<?= isset($_GET['recherche'])?$_GET['recherche'] : '' ?>">
                <label class="gras" for="categorie">Catégorie : </label>
                <select class="select" name="categorie">
                    <option value="">-- Toutes --</option>
                    <option value="Aventure" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Aventure')?'selected' : '' ?>>Aventure</option>
                    <option value="Action" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Action')?'selected' : '' ?>>Action</option>
                    <option value="RPG" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'RPG')?'selected' : '' ?>>RPG</option>
                    <option value="Simulation" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Simulation')?'selected' : '' ?>>Simulation</option>
                    <option value="Simulation de vie" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Simulation de vie')?'selected' : '' ?>>Simulation de vie</option>
                    <option value="Sandbox" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Sandbox')?'selected' : '' ?>>Sandbox</option>
                    <option value="Survie" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Survie')?'selected' : '' ?>>Survie</option>
                    <option value="Horreur" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Horreur')?'selected' : '' ?>>Horreur</option>
                    <option value="Coop" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Coop')?'selected' : '' ?>>Coop</option>
                    <option value="Multijoueur" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Multijoueur')?'selected' : '' ?>>Multijoueur</option>
                    <option value="Course" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Course')?'selected' : '' ?>>Course</option>
                    <option value="Party game" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Party game')?'selected' : '' ?>>Party game</option>
                    <option value="Platforme" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Platforme')?'selected' : '' ?>>Platforme</option>
                    <option value="Battle Royale" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Battle Royale')?'selected' : '' ?>>Battle Royale</option>
                    <option value="FPS" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'FPS')?'selected' : '' ?>>FPS</option>
                    <option value="TPS" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'TPS')?'selected' : '' ?>>TPS</option>
                    <option value="Stratégie" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Stratégie')?'selected' : '' ?>>Stratégie</option>
                    <option value="Puzzle" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Puzzle')?'selected' : '' ?>>Puzzle</option>
                    <option value="Arcade" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Arcade')?'selected' : '' ?>>Arcade</option>
                    <option value="Endless runner" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Endless runner')?'selected' : '' ?>>Endless runner</option>
                    <option value="Design" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Design')?'selected' : '' ?>>Design</option>
                    <option value="Création" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Création')?'selected' : '' ?>>Création</option>
                    <option value="Exploration" <?= (isset($_GET['categorie']) && $_GET['categorie'] === 'Exploration')?'selected' : '' ?>>Exploration</option>
                </select>

                <input class="button3" type="submit" value="Rechercher">
            </form>
        </div>

        <?php  
            $liste_jeux=getIndex($flux);
            displayIndex($liste_jeux);
        ?>

    </main>

    <?php include ("static/footer.php"); ?>

</body >

<?php
    closeDB($flux);
?>

</html>