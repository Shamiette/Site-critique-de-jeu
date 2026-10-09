<?php

    function getIndex($mysqli) {
        $query = "SELECT article.id_article, pseudo, titre_article, contenu, note_redac, date_crea_article, date_der_modif, article.id_jeu, chemin_jaquette, nom_jeu, synopsis, prix, date_sortie
                FROM article 
                INNER JOIN jeu ON article.id_jeu = jeu.id_jeu";

        $whereClauses = [];

        // Recherche texte
        if (!empty($_GET['recherche'])) {
            $recherche = '%' . $mysqli->real_escape_string($_GET['recherche']) . '%';
            $whereClauses[] = "(nom_jeu LIKE '$recherche')";
        }

        // Catégorie
        if (!empty($_GET['categorie'])) {
            $categorie = $mysqli->real_escape_string($_GET['categorie']);
            $whereClauses[] = "article.id_jeu IN (
                                    SELECT id_jeu 
                                    FROM estcategorie
                                    INNER JOIN categorie ON estcategorie.id_categorie = categorie.id_categorie
                                    WHERE nom_categorie = '$categorie'
                                )";
        }

        if (!empty($whereClauses)) {
            $query .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        // Trie par ordre de date la plus récente les articles 
        $query .= " ORDER BY date_crea_article DESC";

        // Exécute avec readDB
        return readDB($mysqli, $query);
    }


    function getJeu($mysqli, $jeu){
        $query = "SELECT article.id_article, jeu.nom_jeu, article.pseudo, titre_article, contenu, note_redac, date_crea_article, date_der_modif, article.id_jeu, titre_article, contenu, note_redac, chemin_jaquette, jeu.nom_jeu, synopsis, prix, date_sortie, imageprofil.chemin_image_profil
            FROM article 
            INNER JOIN jeu ON article.id_jeu = jeu.id_jeu
            INNER JOIN compte ON compte.pseudo=article.pseudo
            INNER JOIN imageprofil ON compte.id_image_profil=imageprofil.id_image_profil
            WHERE article.id_jeu=$jeu";
        $res = readDB($mysqli,$query);
        return $res;
    }

    function getToutLesJeux($mysqli){
        $query = "SELECT id_jeu, nom_jeu
        FROM jeu";
        $res = readDB($mysqli,$query);
        return $res;
    }


    function getArticleJeu($mysqli, $jeu){
        $query = "SELECT article.id_article
            FROM article 
            INNER JOIN jeu ON article.id_jeu = jeu.id_jeu
            WHERE article.id_jeu=$jeu";
        $res = readDB($mysqli,$query);
        return $res;
    }

    function getImages($mysqli, $article){
        $query = "SELECT article.id_article, image.chemin_im
            FROM article 
            INNER JOIN image ON article.id_article = image.id_article
            WHERE article.id_article=$article";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getCategorie($mysqli, $jeu){
        $query = "SELECT article.id_article, categorie.nom_categorie
            FROM article
            INNER JOIN jeu ON article.id_jeu=jeu.id_jeu
            INNER JOIN estcategorie ON article.id_jeu = estcategorie.id_jeu
            INNER JOIN categorie ON estcategorie.id_categorie = categorie.id_categorie
            WHERE article.id_jeu=$jeu";
        $res = readDB($mysqli, $query);
        return $res;
    }



    function getIdCategorie($mysqli, $categorie){
        $query = "SELECT id_categorie, nom_categorie
            FROM categorie
            WHERE nom_categorie='$categorie'";
        $res = readDB($mysqli, $query);
        return $res;
    }


    function getIdSupport($mysqli, $support){
        $query = "SELECT id_support, nom_support
            FROM support
            WHERE nom_support='$support'";
        $res = readDB($mysqli, $query);
        return $res;
    }


    function getTouteLesCategories($mysqli){
        $query = "SELECT nom_categorie, id_categorie
            FROM categorie";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getSupport($mysqli, $jeu){
            $query = "SELECT article.id_article, support.nom_support
            FROM article
            INNER JOIN jeu ON article.id_jeu=jeu.id_jeu
            INNER JOIN estsupport ON article.id_jeu = estsupport.id_jeu
            INNER JOIN support ON estsupport.id_support = support.id_support
            WHERE jeu.id_jeu=$jeu";
            $res = readDB($mysqli, $query);
        return $res;
    }

        function getLesToutSupports($mysqli){
            $query = "SELECT nom_support
            FROM support";
            $res = readDB($mysqli, $query);
        return $res;
    }


    function getAvis($mysqli, $article){
        $query = "SELECT id_avis, avis.pseudo, titre_avis, id_jeu, texte, note, date_crea_avis, imageprofil.chemin_image_profil
            FROM avis
            INNER JOIN compte ON compte.pseudo=avis.pseudo
            INNER JOIN imageprofil ON compte.id_image_profil=imageprofil.id_image_profil
            WHERE id_jeu='$article'";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getAvisJeuCompte($mysqli, $article, $pseudo){
        $query = "SELECT id_avis, pseudo, titre_avis, id_jeu, texte, note, date_crea_avis
            FROM avis
            WHERE id_jeu='$article' AND pseudo='$pseudo'";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getAvisCompte($mysqli, $pseudo){
        $query = "SELECT id_avis, avis.pseudo, titre_avis, jeu.id_jeu, texte, note, date_crea_avis, nom_jeu
            FROM avis
            INNER JOIN jeu ON jeu.id_jeu=avis.id_jeu
            INNER JOIN compte ON avis.pseudo=compte.pseudo
            WHERE avis.pseudo='$pseudo'";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getArticleCompte($mysqli, $pseudo){
        $query = "SELECT article.pseudo, id_article, article.id_jeu, titre_article, contenu, note_redac, date_crea_article, date_der_modif, jeu.nom_jeu
            FROM article
            INNER JOIN jeu
            ON article.id_jeu=jeu.id_jeu
            WHERE pseudo='$pseudo'";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getCompte($mysqli, $login, $password){
        $query = "SELECT pseudo, mdp, nom, prenom, adresse_mail, date_naiss, date_crea_compte, date_der_co, niveau
        FROM compte
        WHERE pseudo='$login' AND mdp='$password'";
        $res = readDB($mysqli, $query);
        return $res;
    }



    function getProfil($mysqli, $login){
        $query = "SELECT pseudo, mdp, nom, prenom, adresse_mail, date_naiss, date_crea_compte, date_der_co, niveau, chemin_image_profil, niveau
        FROM compte
        INNER JOIN imageprofil ON compte.id_image_profil=imageprofil.id_image_profil
        WHERE pseudo='$login'";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function getImagesProfil($mysqli){
        $query = "SELECT id_image_profil, chemin_image_profil
        FROM imageprofil";
        $res = readDB($mysqli, $query);
        return $res;
    }

    function updateDateConnexion($mysqli, $pseudo){
        $date=date("Y-m-d");
        $query = "UPDATE compte SET date_der_co='$date'
                  WHERE '$pseudo' = pseudo";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function creerAvis($mysqli, $login, $titre, $texte, $note, $jeu){
        $date = date("Y-m-d");

        // Échapper les valeurs pour éviter de casser la requête avec des apostrophes
        $login_esc = $mysqli->real_escape_string($login);
        $titre_esc = $mysqli->real_escape_string($titre);
        $texte_esc = $mysqli->real_escape_string($texte);
        $note_esc = (int)$note;
        $jeu_esc = (int)$jeu;

        $query = "INSERT INTO avis (pseudo, id_jeu, titre_avis, texte, note, date_crea_avis) 
                  VALUES ('$login_esc', '$jeu_esc', '$titre_esc', '$texte_esc', $note_esc, '$date')";
        
        $res = writeDB($mysqli, $query);
    }

    function modifierAvis($mysqli, $login, $titre, $texte, $note, $jeu){
        $date=date("Y-m-d");

        $login_esc = $mysqli->real_escape_string($login);
        $titre_esc = $mysqli->real_escape_string($titre);
        $texte_esc = $mysqli->real_escape_string($texte);

        $query = "UPDATE avis SET titre_avis='$titre_esc', texte='$texte_esc', note='$note'
                  WHERE '$login_esc' = pseudo AND id_jeu='$jeu' ";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function supprimeAvis($mysqli, $login, $jeu){
        $query = "DELETE FROM avis
                  WHERE pseudo='$login' AND id_jeu='$jeu' ";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function associeCategorie($mysqli, $jeu, $categorie){
        $query = "INSERT INTO estcategorie 
        (id_jeu, id_categorie)
        VALUES ('$jeu', '$categorie')";
        $res = writeDB($mysqli, $query);
    }

    function associeSupport($mysqli, $jeu, $support){
        $query = "INSERT INTO estsupport
        (id_jeu, id_support)
        VALUES ('$jeu', '$support')";
        $res = writeDB($mysqli, $query);
    }


    function creerArticle($mysqli, $login, $titre, $texte, $note, $jeu){
        $date = date("Y-m-d");

        $login_esc = $mysqli->real_escape_string($login);
        $titre_esc = $mysqli->real_escape_string($titre);
        $texte_esc = $mysqli->real_escape_string($texte);
        $note_esc = (int)$note;
        $jeu_esc = (int)$jeu;

        $query = "INSERT INTO article
            (pseudo, id_jeu, titre_article, contenu, note_redac, date_crea_article, date_der_modif)
            VALUES ('$login_esc', $jeu_esc, '$titre_esc', '$texte_esc', $note_esc, '$date', '$date')";

        $res = writeDB($mysqli, $query);
    }

    function modifierArticle($mysqli, $login, $titre, $texte, $note, $jeu){
        $date=date("Y-m-d");

        $login_esc = $mysqli->real_escape_string($login);
        $titre_esc = $mysqli->real_escape_string($titre);
        $texte_esc = $mysqli->real_escape_string($texte);

        $query = "UPDATE article SET titre_article='$titre_esc', contenu='$texte_esc', note_redac='$note', date_der_modif='$date'
            WHERE '$login_esc' = pseudo AND id_jeu='$jeu'";
        $res = writeDB($mysqli, $query);
        return $res;
    }


    function supprimeArticle($mysqli, $pseudo, $jeu){
        writeDB($mysqli, "DELETE FROM estcategorie
                          WHERE estcategorie.id_jeu = '$jeu'");

        writeDB($mysqli, "DELETE FROM estsupport
                          WHERE estsupport.id_jeu = '$jeu'");

        writeDB($mysqli, "DELETE avis FROM avis
                          JOIN article ON article.id_jeu = avis.id_jeu
                          WHERE article.pseudo = '$pseudo' AND article.id_jeu = '$jeu'");

        writeDB($mysqli, "DELETE image FROM image 
                          JOIN article ON article.id_article = image.id_article 
                          WHERE article.pseudo = '$pseudo' AND article.id_jeu = '$jeu'");

        $query = "DELETE FROM article
                  WHERE pseudo='$pseudo' AND id_jeu='$jeu' ";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function creationCompte($mysqli, $pseudo, $mdp, $nom,$prenom, $adresse_mail, $date_naiss, $image){
        $date=date("Y-m-d");
        $query = "INSERT INTO compte (pseudo, mdp, nom, prenom, adresse_mail, date_naiss, date_crea_compte, date_der_co, niveau, id_image_profil) VALUES ('$pseudo', '$mdp', '$nom', '$prenom', '$adresse_mail', '$date_naiss', '$date', '$date', 'utilisateur', '$image')";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function modificationCompte($mysqli, $pseudo, $mdp, $nom,$prenom, $adresse_mail, $date_naiss, $image, $date_crea_compte, $date_der_co, $niveau){
        $date=date("Y-m-d");
        $query = "UPDATE compte SET pseudo='$pseudo', mdp='$mdp', nom='$nom', prenom='$prenom', adresse_mail='$adresse_mail', date_naiss='$date_naiss', date_crea_compte='$date_crea_compte', date_der_co='$date_der_co', niveau='$niveau', id_image_profil='$image'
        WHERE pseudo='$pseudo'";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function modifieNiveau($mysqli, $pseudo, $niveau){
        $query = "UPDATE compte SET niveau='$niveau'
        WHERE pseudo='$pseudo'";
        $res = writeDB($mysqli, $query);
        return $res;
    }

    function supprimeCompte($mysqli, $login) {
        $login_esc = $mysqli->real_escape_string($login);

        $result = mysqli_query($mysqli, "SELECT id_jeu FROM article WHERE pseudo = '$login_esc'");

        while ($row = mysqli_fetch_assoc($result)) {
            $id_jeu = $row['id_jeu'];
            supprimeArticle($mysqli, $login_esc, $id_jeu);
        }

        $res = writeDB($mysqli, "DELETE FROM compte WHERE pseudo = '$login_esc'");
        return $res;
    }

?>
