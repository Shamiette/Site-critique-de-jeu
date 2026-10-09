<!DOCTYPE html>
<html lang="fr">

<!DOCTYPE html>
<html lang="fr">

<?php include ("static/head.php"); ?>

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
                        echo '<a href="connexion.php" class="active">Connexion</a>';
                        echo '<li><a href="creer_compte.php">Créer un compte</a></li> ';
                    }
                ?>
            </li>
        </ul>
    </nav>

	<main>
        <div class="container_form">
            <h3 class="titre">Connexion à un compte : </h3>

            <form action="php/login.php" method="POST">
                <div>
                    <label for="login" class="sous-titre">Nom d'utilisateur :</label><br>
                    <input type="text" id="login" name="login" class="form-control" required>
                    <!-- required : le formulaire ne peut être validé que si le champ est rempli -->
                </div>

                <div>
                    <label for="password" class="sous-titre">Mot de passe :</label><br>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="button">Se connecter</button>
            </form>
        </div>
    </main>

    <?php include ("static/footer.php"); ?>

</body>

</html>