<?php
require_once 'data/user_test.php';
$bdd = db();

if (est_admin() !== 'administrateur') {
    header('Location: index.php');
    exit();
}

// recupere toutes les annonces
$requete = $bdd->query("
    SELECT annonces.*, users.prenom, users.nom 
    FROM annonces 
    JOIN users ON annonces.user_id = users.id 
    ORDER BY annonces.creation_date DESC
");
$all_posts = $requete->fetchAll();

// recupere le statut de l'annonce
$status_request = $bdd->query(
    "
    SELECT annonces.statut 
    FROM annonces"
);
$get_status = $status_request->fetchColumn();

# boutons valider / refuser
if (isset($_POST['action'])) {
    $action_choisie = $_POST['action'];
    $id_annonce = $_POST['annonce_id'];

    if ($action_choisie == 'VALIDER') {
        $update = $bdd->prepare("UPDATE annonces SET statut = 'validee', motif_refus = NULL WHERE id = ?");
        $update->execute([$id_annonce]);
    }

    if ($action_choisie == 'REFUSER') {
        $update = $bdd->prepare("UPDATE annonces SET statut = 'refusee', motif_refus = 'contenu inapproprié' WHERE id = ?");
        $update->execute([$id_annonce]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Gestion des Posts</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div style="display: flex; align-items: flex-start; padding-top: 135px;">
        <div class="total-posts">
            <?php foreach ($all_posts as $post) { ?>
                <div class="publication">
                    <div class="publication-top">
                        <?php
                        if ($post['statut'] == "en_attente") {
                            $classe = "statut-attente";
                            $texte = "EN ATTENTE";
                        } elseif ($post['statut'] == "validee") {
                            $classe = "statut-validee";
                            $texte = "VALIDÉE";
                        } elseif ($post['statut'] == "refusee") {
                            $classe = "statut-refusee";
                            $texte = "REFUSÉE";
                        }
                        ?>
                        <div class="statut-post">
                            <h2 class="<?php echo $classe; ?>"><?php echo $texte; ?></h2>
                        </div>
                        <h1><?php echo ($post['titre']); ?></h1>
                        <h2><strong>Auteur : </strong><?php echo ($post['prenom']); ?></h2>
                        <?php $photos = explode(',', $post['url_photo']); ?>
                        <img src="<?php echo ($photos[0]); ?>" width="350" height="350"></img>
                        <br>
                        <h3>Description : <?php echo ($post['description']); ?></h3>
                    </div>
                    <div class="publication-bottom">
                        <div class="ligne-info">
                            <h3>Catégorie : <span><?php echo ($post['categorie']); ?></span></h3>
                        </div>
                        <div class="ligne-info">
                            <h3>Sous-catégorie : <span><?php echo ($post['sous_categorie']); ?></span></h3>
                        </div>
                        <div class="ligne-info">
                            <h3>Etat : <span><?php echo ($post['etat']); ?></span></h3>
                        </div>
                        <div class="ligne-info">
                            <h3>Prix : <span><?php echo ($post['prix']); ?> $</span></h3>
                        </div>
                        <div class="ligne-info">
                            <h3>Ville : <span><?php echo ($post['ville']); ?></span></h3>
                        </div>
                    </div>
                    <div class="view-button">
                        <a href="#"><strong>VOIR</strong></a>
                    </div>
                    <form method="POST" class="admin-buttons">
                        <input type="hidden" name="annonce_id" value="<?php echo $post['id']; ?>">

                        <input type="submit" name="action" class="accept-button" value="VALIDER">
                        <input type="submit" name="action" class="remove-button" value="REFUSER">
                    </form>
                </div>
            <?php } ?>
        </div>
        <div class="zone-menu-admin">
            <div class="encadre-menu">
                <h3>Gestion admin</h3>
                <a href="dashboard_admin.php" class="btn-menu-admin inactif">Modération Annonces</a>
                <a href="gestion_personnes.php" class="btn-menu-admin inactif">Gestion personne</a>
                <a href=" visualisation_posts.php" class="btn-menu-admin inactif">Visualisation de tous les posts</a>
                <a href="graphes_stats.php" class="btn-menu-admin inactif">Evolution des statistiques</a>
                <a href="gestion_categories.php" class="btn-menu-admin inactif">Gestion catégories</a>
            </div>
        </div>
    </div>
</body>

</html>