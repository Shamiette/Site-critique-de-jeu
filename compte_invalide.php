<!DOCTYPE html>
<html lang="fr">

<?php include ("static/head.php");
$status=$_GET['status'];
?>
<body>
    <?php include ("static/header.php"); ?>
    
    <nav>

    </nav>

	<main>
    <?php
        if ($status=='existe'){
            echo '<p class="erreur">Le nom d\'utilisateur que vous avez choisi est déjà associé à un compte</p>';
            echo '<a class="button4" href="./creer_compte.php">Retourner à la création de mon compte</a>';
        }
        if ($status=='age'){
            echo '<p class="erreur">Vous n\'avez pas l\'âge requis pour créer un compte sur notre site</p>';
            echo '<a class="button4" href="./index.php">Retourner à la page d\'accueil</a>';
        }
        ?>
    </main>

    <?php include ("static/footer.php"); ?>

</body>

</html>