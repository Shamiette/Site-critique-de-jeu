<?php

    function displayIndex($liste_jeux) {
        $articles_par_page = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $debut = ($page - 1) * $articles_par_page;
        $jeux_a_afficher = array_slice($liste_jeux, $debut, $articles_par_page);

        foreach ($jeux_a_afficher as $jeu) {
            echo '<div class="card-container">' ;
                echo '<a href="article.php?numero='.$jeu['id_jeu'].'">' ;
                    echo '<div class="card">' ;
                        echo '<div>' ;
                            echo '<img src="'.$jeu['chemin_jaquette'].'" alt="'.$jeu['nom_jeu'].'" class="index-image">' ;
                        echo '</div>' ;
                        echo '<div>' ;
                            echo '<p class="titre-article">'.$jeu['nom_jeu'].'</p>' ;
                            echo '<p>'.$jeu['synopsis'].'</p>' ;
                            echo '<p>Prix : '.$jeu['prix'].'€</p>' ;
                            echo '<p>Date de sortie du jeu: '.$jeu['date_sortie'].'</p>' ;
                            echo '<p>Date de publication de l\'article: '.$jeu['date_crea_article'].'</p>' ;
                        echo '</div>';
                    echo '</div>';
                echo '</a>' ;
            echo '</div>';
        }
        $total_articles = count($liste_jeux);
        $total_pages = ceil($total_articles / $articles_par_page);
        
        /* Récupération de recherche par texte et catégorie */
        $recherche = isset($_GET['recherche'])?urlencode($_GET['recherche']) : '';
        $categorie = isset($_GET['categorie'])?urlencode($_GET['categorie']) : '';
        
        echo '<div class="pagination-container">';
            // Bouton précédent
            if ($page > 1) {
                echo '<a class="bouton-pagination" href="index.php?page='.($page - 1).'&recherche='.$recherche.'&categorie='.$categorie.'">Page précédente</a>';
            }

            // Liens vers chaque page
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<span class="active-page-link">'.$i.'</span> ';
                } else {
                    echo '<a class="page-link" href="index.php?page='.$i.'&recherche='.$recherche.'&categorie='.$categorie.'">'.$i.'</a> ';
                }
            }

            // Bouton suivant
            if ($page < $total_pages) {
                echo '<a class="bouton-pagination" href="index.php?page='.($page + 1).'&recherche='.$recherche.'&categorie='.$categorie.'">Page suivante</a>';
            }

            echo '</div>';
        echo '</div>';
    }

    function displayJeu($flux, $jeu, $pseudo){
        $infos = getJeu($flux, $jeu);
        $images=getImages($flux, $jeu);
        $categories=getCategorie($flux, $jeu);
        $supports=getSupport($flux, $jeu);
        $liste_avis=getAvis($flux, $jeu);
        if (!empty($pseudo)){
            $avisPersonnel=getAvisJeuCompte($flux, $jeu, $pseudo);
            
            $compte=getProfil($flux, $pseudo);
            $niveau=$compte[0]['niveau'];
        }

        $somme_notes=0;
        $nb_notes=0;
        
        echo '<div class="margin">';
            echo '<div class="card">' ;
                echo '<img src="'.$infos[0]['chemin_jaquette'].'" alt="'.$infos[0]['nom_jeu'].'" class="article-image">' ;
                echo '<div>' ;
                    echo '<p class="titre-article">'.$infos[0]['nom_jeu'].'</p>' ;
                    echo '<p>Prix : '.$infos[0]['prix'].'€</p>' ;
                    echo '<p>Date de sortie : '.$infos[0]['date_sortie'].'</p>' ;
                    echo '<p>Synopsis : '.$infos[0]['synopsis'].'</p>' ;
                    foreach ($liste_avis as $avis){
                        $somme_notes=$somme_notes+$avis['note'];
                        $nb_notes=$nb_notes+1;
                    }
                    if ($nb_notes!=0){
                        $note_moy=$somme_notes/$nb_notes;
                        $note_moy_arrondie=round($note_moy,1, PHP_ROUND_HALF_UP);
                        echo '<p>Note moyenne: '.$note_moy_arrondie.'/10</p>' ;
                    }
                    
            echo '</div>' ;
        echo '</div>' ;

        echo '<p class="titre">Article :</p>';
        echo '<div class="card-container">' ;
            echo '<div class="card">' ;
                echo '<div class="profil-box">' ;
                    echo '<img src="'.$infos[0]['chemin_image_profil'].'" alt="'.$infos[0]['pseudo'].'" class="im-pp">';
                    echo '<a href="profil.php?pseudo='.$infos[0]['pseudo'].'"><p class="pseudo">'.$infos[0]['pseudo'].'</p></a>' ;
                echo '</div>' ;
                echo '<div>' ;
                    echo '<p class="titre2">'.$infos[0]['titre_article'].'</p>' ;
                    echo '<p>'.$infos[0]['contenu'].'</p>' ;
                    echo "<p>Date de création de l'article: ".$infos[0]['date_crea_article']."</p>" ;
                    echo "<p>Date de dernière modification de l'article: ".$infos[0]['date_der_modif']."</p>" ;
                    echo '<p class="gras">Note rédacteur : '.$infos[0]['note_redac'].'</p>' ;
                echo '</div>';
            echo '</div>';

            echo '<p class="sous-titre">Catégories :</p>';
            echo '<div class="card">' ;
                foreach ($categories as $categorie){

                    echo '<div class="little-card">' ;
                        echo '<p>'.$categorie['nom_categorie'].'</p>' ;
                    echo '</div>';
                }
            echo '</div>';

            echo '<p class="sous-titre">Supports :</p>';
            echo '<div class="card">' ;
            foreach ($supports as $support){
                echo '<div class="little-card">' ;
                    echo '<p>'.$support['nom_support'].'</p>' ;
                echo '</div>';
            }
            echo '</div>';

            if (!empty($images)){
                echo '<p class="sous-titre">Images de Gameplay :</p>';
                foreach ($images as $image){
                    echo '<img src="'.$image['chemin_im'].'" alt="'.$infos[0]['nom_jeu'].'" class="article-image">' ;
                }
            }
            $compte_article=getProfil($flux, $infos[0]['pseudo']);
            $niveau_article=$compte_article[0]['niveau'];
            if (!empty($pseudo)){
                if ($niveau=='admin' && $niveau_article!='admin'){
                    echo '<a class="button2" href="php/creer_modifier_article.php?jeu='.$infos[0]['id_jeu'].'&pseudo='.$infos[0]['pseudo'].'&status=supprime">Supprimer cet article</a>';
                }
            }
            echo '</div>';
        
        if (isset($liste_avis[0])){
            echo '<p class="titre"> Avis :</p>';
        }
        echo '<div class="avis-grid">';
            foreach ($liste_avis as $avis){
                echo '<div class="card-container">' ;
                    echo '<div class="ligne">' ;
                        echo '<div>';
                            echo '<p class="titre2">'.$avis['titre_avis'].'</p>' ;
                        echo '</div>';
                        echo '<div class="profil-box">' ;
                            echo '<img src="'.$avis['chemin_image_profil'].'" alt="'.$avis['pseudo'].'" class="im-pp2">' ;
                            echo '<a href="profil.php?pseudo='.$avis['pseudo'].'">'.$avis['pseudo'].'</a>' ;
                        echo '</div>' ;
                    echo '</div>' ;
                    echo '<div>' ;
                        echo '<p>'.$avis['texte'].'</p>' ;
                        echo '<p>Date de l\'avis : ' . $avis['date_crea_avis'] . '</p>';
                        echo '<p class="gras">Note donnée par l\'utilisateur : ' . $avis['note'] . '/10</p>';
                    echo '</div>';
                $compte_avis=getProfil($flux, $avis['pseudo']);
                $niveau_avis=$compte_avis[0]['niveau'];
                if (!empty($pseudo)){
                    if ($niveau=='admin' && $niveau_avis!='admin'){
                        echo '<p><a class="button2" href="php/creer_modifier_avis.php?jeu='.$infos[0]['id_jeu'].'&pseudo='.$avis['pseudo'].'&status=supprime_autre">Supprimer l\'avis de '.$avis['pseudo'].'</a></p>';
                    }
                }
                echo '</div>';
            }

            // Si seulement un avis : on ajoute une colonne vide pour que cela soit quand même répartit sur deux colonnes
            if (count($liste_avis) === 1) {
                echo '<div class="card-container2 empty"></div>';
            }
            echo '</div>';
            
            if (!empty($pseudo)){
                echo '<div class=container_form>';
                if (!isset($avisPersonnel) || empty($avisPersonnel)){
                    echo '<p class="titre">Créer votre avis : </p>' ;
                    echo '<form action="php/creer_modifier_avis.php?jeu='.$infos[0]['id_jeu'].'&status=creation" method="POST">';
                        echo '<div>';
                            echo '<label for="titre" class="sous-titre">Titre de votre avis : </label><br>';
                            echo '<input type="text" id="titre" name="titre" class="form-control" required>';
                        echo '</div>';
                        echo '<div>';
                            echo '<label for="texte" class="sous-titre">Contenu de votre avis : </label><br>';
                            echo '<textarea id="texte" name="texte" class="form-control" rows="4" required></textarea>';
                        echo '</div>';
                        echo '<div>';
                            echo '<label for="note" class="sous-titre">Note sur 10 : </label>';
                            echo '<select class="text" id="note" name="note">';
                                for ($i = 0; $i <= 10; $i++) {
                                    if ($i==5){
                                        echo "<option value=\"$i\" selected>$i</option>";
                                    }else{
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                }
                            echo '</select>';
                        echo '</div>';
                        echo '<button type="submit" class="button">Créer votre avis</button>';
                    echo '</form>';
                }else{
                    echo '<p class="titre">Modifier votre avis : </p>' ;
                    echo '<form action="php/creer_modifier_avis.php?jeu='.$infos[0]['id_jeu'].'&status=modification" method="POST">';
                    echo '<div>';
                    echo '<label for="titre" class="sous-titre">Titre de votre avis : </label><br>';
                    echo '<input type="text" id="titre" name="titre" class="form-control" value="'. $avisPersonnel[0]['titre_avis'] . '" required>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label for="texte" class="sous-titre">Contenu de votre avis : </label><br>';
                    echo '<textarea id="texte" name="texte" class="form-control" rows="4" required>'.$avisPersonnel[0]['texte'].'</textarea>';
                    echo '</div>';

                    echo '<div>';
                    echo '<label for="note" class="sous-titre">Note sur 10 : </label>';
                    echo '<select class="text" id="note" name="note">';
   
                    for ($i = 0; $i <= 10; $i++) {
                        if ($i==$avisPersonnel[0]['note']){
                            echo "<option value=\"$i\" selected>$i</option>";
                        }else{
                            echo "<option value=\"$i\">$i</option>";
                        }
                    }
                    echo '</select>';
                    echo '</div>';
                    echo '<button type="submit" class="button">Valider les modifications</button>';
                    echo '</form>';

                    echo '<p><a class="button2" href="php/creer_modifier_avis.php?jeu='.$infos[0]['id_jeu'].'&status=supprime">Supprimer mon avis</a></p>';
                }
                echo '</div>';
            }
        echo '</div>' ;
    }

    function displayAvisCompte($flux, $login){
        $liste_avis_compte=getAvisCompte($flux, $login);
        echo '<div class="container_form">' ;
            foreach ($liste_avis_compte as $avis_compte){
                echo '<div class="card-container3">' ;
                    echo '<p><a class="titre" href="article.php?numero='.$avis_compte['id_jeu'].'">'.$avis_compte['nom_jeu'].'</a></p>' ;
                    echo '<form action="php/creer_modifier_avis.php?jeu='.$avis_compte['id_jeu'].'&status=modification" method="POST">';
                        echo '<div>';
                        echo '<label for="titre_'.$avis_compte['id_jeu'].'" class="sous-titre">Titre de votre avis: </label><br>';
                        echo '<input type="text" id="titre_'.$avis_compte['id_jeu'].'" name="titre" class="form-control" value="'. $avis_compte['titre_avis'] . '" required>';
                        echo '</div>';
                        echo '<div>';
                        echo '<label for="texte_'.$avis_compte['id_jeu'].'" class="sous-titre">Contenu de votre avis: </label><br>';
                        echo '<textarea id="texte_'.$avis_compte['id_jeu'].'" name="texte" class="form-control" rows="4" required>'.$avis_compte['texte'].'</textarea>';
                        echo '</div>';
                        echo '<div>';
                        echo '<label for="note_'.$avis_compte['id_jeu'].'" class="text">Note sur 10 : </label>';
                        echo '<select id="note_'.$avis_compte['id_jeu'].'" name="note" class="text">';

                        for ($i = 0; $i <= 10; $i++) {
                            if ($i==$avis_compte['note']){
                                echo "<option value=\"$i\" selected>$i</option>";
                            }else{
                                echo "<option value=\"$i\">$i</option>";
                            }
                        }
                        echo '</select>';
                        echo '</div>';
                        echo '<p>Date de création de l\'avis : '.$avis_compte['date_crea_avis'].'</p>' ;
                        echo '<button type="submit" class="button">Changer mon avis</button>';
                    echo '</form>';
                    echo '<a class="button2" href="php/creer_modifier_avis.php?jeu='.$avis_compte['id_jeu'].'&status=supprime">Supprimer mon avis</a>';
                echo '</div>' ;
            }
        echo '</div>' ;
    }

    function displayArticleCompte($flux, $login){
        $liste_article_compte=getArticleCompte($flux, $login);
        echo '<div class="container_form">' ;
            foreach ($liste_article_compte as $article_compte){
                echo '<div class="card-container3">' ;
                    echo '<p><a class="titre" href="article.php?numero='.$article_compte['id_jeu'].'">'.$article_compte['nom_jeu'].'</a></p>' ;
                    echo '<form action="php/creer_modifier_article.php?jeu='.$article_compte['id_jeu'].'&pseudo='.$article_compte['pseudo'].'&status=modification" method="POST">';
                        echo '<div>';
                        echo '<label for="titre_'.$article_compte['id_jeu'].'" class="sous-titre">Titre de votre article: </label><br>';
                        echo '<input type="text" id="titre_'.$article_compte['id_jeu'].'" name="titre" class="form-control" value="'. $article_compte['titre_article'] . '" required>';
                        echo '</div>';
                        echo '<div>';
                        echo '<label for="texte_'.$article_compte['id_jeu'].'" class="sous-titre">Contenu de votre avis: </label><br>';
                        echo '<textarea type="text" id="texte_'.$article_compte['id_jeu'].'" name="texte" class="form-control" rows="4" required>'.$article_compte['contenu'].'</textarea>';
                        echo '</div>';

                        echo '<div>';
                        echo '<label for="note_'.$article_compte['id_jeu'].'" class="text">Note sur 10 : </label>';
                        echo '<select id="note_'.$article_compte['id_jeu'].'" name="note" class="text">';
                        for ($i = 0; $i <= 10; $i++) {
                            if ($i==$article_compte['note_redac']){
                                echo "<option value=\"$i\" selected>$i</option>";
                            }else{
                                echo "<option value=\"$i\">$i</option>";
                            }
                        }

                        echo '</select>';
                        echo '</div>';
                        echo '<p>Date de création de l\'article : '.$article_compte['date_crea_article'].'</p>' ;
                        echo '<p>Date de dernière modification de l\'article : '.$article_compte['date_der_modif'].'</p>' ;
                        echo '<button type="submit" class="button">Modifier mon article</button>';
                    echo '</form>';
                    echo '<a class="button2" href="php/creer_modifier_article.php?jeu='.$article_compte['id_jeu'].'&pseudo='.$article_compte['pseudo'].'&status=supprime">Supprimer mon article</a>';
                echo '</div>' ;
            }
        echo '</div>' ;
    }

    function displayProfil($flux, $login_regarde, $login_actuel){
        $profil = getProfil($flux, $login_regarde);
        $liste_images_profil=getImagesProfil($flux);
        $compte_actuel=getProfil($flux, $login_actuel);
        if ($login_actuel='' || $login_actuel==$login_regarde){
            echo '<div class="container_form">';
                echo '<div class="card top">' ;
                    echo '<img src="'.$profil[0]['chemin_image_profil'].'" alt="'.$profil[0]['pseudo'].'" class="im-pp">' ;
                    echo '<div class="left">';
                        echo '<p class="sous-titre">'.$profil[0]['pseudo'].'</p>';
                        echo '<p>Date de création: '.$profil[0]['date_crea_compte'].'</p>' ;
                        echo '<p>Dernière connexion: '.$profil[0]['date_der_co'].'</p>' ;
                        echo '<p>Niveau: '.$profil[0]['niveau'].'</p>' ;
                    echo '</div>';
                echo '</div>' ;

                echo '<form action="php/modification_compte.php" method="POST">'; 
                    echo '<p class="titre"> Modification du compte : </p>';
                    echo '<p class="sous-titre"> Changement de photo de profil : </p>';
                    foreach ($liste_images_profil as $image_profil){
                        if ($profil[0]['chemin_image_profil']==$image_profil['chemin_image_profil']){
                            echo '<label>';
                            echo '<input type="radio" class="hidden-radio" id="'.$image_profil['id_image_profil'].'" name="image_profil" value="'.$image_profil['id_image_profil'].'" checked/>';
                            echo '<label for="'.$image_profil['id_image_profil'].'"  class="image-option">';
                            echo '<img class="profil-img" src="'.$image_profil['chemin_image_profil'].'" alt="Image 0" class="changement_pp"">';
                            echo '</label>';
                        }else{
                            echo '<label>';
                            echo '<input type="radio" class="hidden-radio" id="'.$image_profil['id_image_profil'].'" name="image_profil" value="'.$image_profil['id_image_profil'].'" />';
                            echo '<label for="'.$image_profil['id_image_profil'].'"  class="image-option">';
                            echo '<img class="profil-img" src="'.$image_profil['chemin_image_profil'].'" alt="Image 0" class="changement_pp"">';
                            echo '</label>';
                        }
                    }
                    echo '<div>';
                        echo '<label for="password" class="sous-titre">Mot de passe :</label><br>';
                    echo '</div>';
                    
                    echo '<input type="text" id="password" name="password" class="form-control" value="'. $profil[0]['mdp'] . '" required>';
                    
                    echo '<div>';
                        echo '<label for="nom" class="sous-titre">Nom :</label><br>';
                        echo '<input type="text" id="nom" name="nom" class="form-control" value="'. $profil[0]['nom'] . '" required>';
                    echo '</div>';
                    echo '<div>';
                        echo '<label for="prenom" class="sous-titre">Prénom :</label><br>';
                        echo '<input type="text" id="prenom" name="prenom" class="form-control" value="'. $profil[0]['prenom'] . '" required>';
                    echo '</div>';
                    echo '<div>';
                        echo '<label for="adresse_mail" class="sous-titre">Adresse mail :</label><br>';
                        echo '<input type="text" id="adresse_mail" name="adresse_mail" class="form-control" value="'. $profil[0]['adresse_mail'] . '" required>';
                    echo '</div>';
                    echo '<div>';
                        echo '<label for="date_de_naissance" class="sous-titre">Date de naissance : </label>';
                        echo '<input type="date" id="date_de_naissance" name="date_de_naissance" value="'. $profil[0]['date_naiss'] . '" required>';
                    echo '</div>';

                    echo '<button type="submit" class="button">Modifier mon compte</button>';
                echo '</form>';

                echo '<div>';
                    echo '<a href="php/supprime_compte.php?compte='.$profil[0]['pseudo'].'" class="button2">Supprimer mon compte</a>';
                echo '</div>';

            echo '</div>';

        } 
        elseif(!isset($compte_actuel[0]['niveau']) || $compte_actuel[0]['niveau']!="admin" || $profil[0]['niveau']=="admin"){
            echo '<div class="margin">';
                echo '<div class="card">' ;
                    echo '<img src="'.$profil[0]['chemin_image_profil'].'" alt="'.$profil[0]['pseudo'].'" class="im-pp">' ;
                    echo '<div class="left">';
                        echo '<p>'.$profil[0]['pseudo'].'</p>' ;
                        echo '<p>Date de création: '.$profil[0]['date_crea_compte'].'</p>' ;
                        echo '<p>Dernière connexion: '.$profil[0]['date_der_co'].'</p>' ;
                        echo '<p>Niveau: '.$profil[0]['niveau'].'</p>' ;
                        echo '</select>';
                        echo '</div>';

                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }else{
            echo '<div class="container_form">';
                echo '<div class="card top">' ;
                    echo '<img src="'.$profil[0]['chemin_image_profil'].'" alt="'.$profil[0]['pseudo'].'" class="im-pp">' ;
                    $id=1;
                    echo '<div>';
                        echo '<p class="sous-titre">'.$profil[0]['pseudo'].'</p>';
                        echo '<p>Date de création: '.$profil[0]['date_crea_compte'].'</p>' ;
                        echo '<p>Dernière connexion: '.$profil[0]['date_der_co'].'</p>' ;
                        echo '<form action="php/supprime_compte.php?compte='.$profil[0]['pseudo'].'&status=niveau" method="POST">'; 
                        echo '<div>';
                        echo '<label class="text" for="niveau">Niveau du compte: </label>';
                        echo '<select class="text" id="niveau" name="niveau">';
                        if ($profil[0]['niveau'] == 'utilisateur') {
                            echo '<option value="utilisateur" selected>utilisateur</option>';
                            echo '<option value="rédacteur">rédacteur</option>';
                        } else {
                            echo '<option value="utilisateur">utilisateur</option>';
                            echo '<option value="rédacteur" selected>rédacteur</option>';
                        }
                        echo '</select>';
                        echo '<button type="submit" class="button">Modifier le niveau du compte</button>';
                    echo '</div>';
                echo '</form>';

                    echo '</div>';
                echo '</div>' ;
                
                    echo '<p class="sous-titre">Nom : '. $profil[0]['nom'] .'</p>';

                    echo '<p class="sous-titre">Prénom : '. $profil[0]['prenom'] .'</p>';

                    echo '<p class="sous-titre">Date de naissance : '. $profil[0]['date_naiss'] .'</p>';

                    echo '<p class="sous-titre">Adresse mail : '. $profil[0]['adresse_mail'] .'</p>';

                    echo '<a href="php/supprime_compte.php?compte='.$profil[0]['pseudo'].'&status=supprime" class="button2">Supprimer le compte</a>';

        }
    }  

    function displayFormCreaCompte($flux){
        $liste_images_profil=getImagesProfil($flux);
        echo '<div class="container_form">';
        echo '<h3 class="titre">Création du compte:</h3>';
        echo '<form action="php/creation_compte.php" method="POST">'; 
        echo '<div>';
        echo "<label for='login' class='sous-titre'>Nom d'utilisateur :</label><br>";
        echo '<input type="text" id="login" name="login" class="form-control" required>';
        echo '</div>';
        echo '<div>';
        echo '<label for="password" class="sous-titre">Mot de passe :</label><br>';
        echo '<input type="password" id="password" name="password" class="form-control" required>';
        echo '</div>';
        echo '<div>';
        echo '<label for="nom" class="sous-titre">Nom :</label><br>';
        echo '<input type="text" id="nom" name="nom" class="form-control" required>';
        echo '</div>';
        echo '<div>';
        echo '<label for="prenom" class="sous-titre">Prénom :</label><br>';
        echo '<input type="text" id="prenom" name="prenom" class="form-control" required>';
        echo '</div>';
        echo '<div>';
        echo '<label for="adresse_mail" class="sous-titre">Adresse mail :</label><br>';
        echo '<input type="text" id="adresse_mail" name="adresse_mail" class="form-control" required>';
        echo '</div>';
        echo '<div class="bottom">';
        echo '<label for="date_de_naissance" class="sous-titre">Date de naissance :</label>';
        echo '<input type="date" id="date_de_naissance" name="date_de_naissance" required>';
        echo '</div>';
        $id=1;
        foreach ($liste_images_profil as $image_profil){
            if ($image_profil['id_image_profil']==1){
                echo '<label>';
                echo '<input type="radio" class="hidden-radio" id="'.$image_profil['id_image_profil'].'" name="image_profil" value="'.$image_profil['id_image_profil'].'" checked/>';
                echo '<label for="'.$image_profil['id_image_profil'].'"  class="image-option">';
                echo '<img class="profil-img" src="'.$image_profil['chemin_image_profil'].'" alt="Image 0" class="changement_pp"">';
                echo '</label>';
                $id+=1;
            }else{
                echo '<label>';
                echo '<input type="radio" class="hidden-radio" id="'.$image_profil['id_image_profil'].'" name="image_profil" value="'.$image_profil['id_image_profil'].'"/>';
                echo '<label for="'.$image_profil['id_image_profil'].'"  class="image-option">';
                echo '<img class="profil-img" src="'.$image_profil['chemin_image_profil'].'" alt="Image 0" class="changement_pp"">';
                echo '</label>';
                $id+=1;
            }
        }
        echo '<div>';
            echo '<button type="submit" class="button">Créer mon compte</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    }


    function displayFormCreaArticle($flux){
        $jeux=getToutLesJeux($flux);
        $categories=getTouteLesCategories($flux);
        $supports=getLesToutSupports($flux);
        echo '<div class="container_form top">' ;
            echo '<form action="php/creer_modifier_article.php?status=creation" method="POST">';
                echo '<p class="titre">Rédaction d\'un article : </p>';
                echo '<div class="top bottom">';
                    echo '<label class="sous-titre" for="jeu">Jeux concerné : </label>';
                    echo '<select id="jeu" name="jeu" class="text">';
                    foreach($jeux as $jeu){
                        $article_jeu=getArticleJeu($flux, $jeu['id_jeu']);
                        if (empty($article_jeu)){
                            echo "<option value=\"" . $jeu['id_jeu'] . "\" selected>" . $jeu['nom_jeu'] . "</option>";
                        }
                    }
                    echo '</select>';
                echo '</div>';

                echo '<div>';
                    echo '<label for="titre" class="sous-titre">Titre de votre article : </label><br>';
                    echo '<input type="text" id="titre" name="titre" class="form-control" required>';
                echo '</div>';
                echo '<div>';
                    echo '<label for="texte" class="sous-titre">Contenu de votre avis : </label><br>';
                    echo '<textarea id="texte" name="texte" class="form-control" rows="4" required></textarea>';
                echo '</div>';

                echo '<div>';
                echo '<a class="text">Pour sélectionner plusieurs catégories ou supports appuyer sur ctrl et cliquer sur les catégories ou supports voulus </a>';
                echo '<p class="sous-titre">Catégories du jeu : </p>';
                echo '<select name="categorie[]" multiple>';
                foreach($categories as $categorie){
                    echo '<option class="text" value="'.$categorie['nom_categorie'].'">';
                    echo $categorie['nom_categorie'];
                    echo '</option>';
                }
                echo '</select>';
                echo '</div>';

                echo '<div>';
                echo '<p class="sous-titre">Supports du jeu : </p>';
                echo '<select name="support[]" multiple>';
                foreach($supports as $support){
                    echo '<option class="text" value="'.$support['nom_support'].'">';
                    echo $support['nom_support'];
                    echo '</option>';
                }
                echo '</select>';
                echo '</div>';

                echo '<div class="top">';
                    echo '<label class="sous-titre" for="note">Note sur 10 : </label>';
                    echo '<select class="text" id="note" name="note">';
                    for ($i = 0; $i <= 10; $i++) {
                        echo "<option value=\"$i\" >$i</option>";
                    }
                    echo '</select>';
                echo '</div>';
                echo '<button type="submit" class="button">Creer mon article</button>';
            echo '</form>';
        echo '</div>' ;
    }


?>