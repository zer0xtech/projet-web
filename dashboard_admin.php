<?php

require_once 'data/user_test.php';
$bdd = db();

// check si l'utilisateur est un modérateur / admin 
if (est_admin() !== 'administrateur' && est_admin() !== 'moderateur') {
    header('Location: index.php');
    exit();
}

# Les différents boutons 
if (isset($_POST['action'])) {
    $action_choisie = $_POST['action'];
    $id_annonce = $_POST['annonce_id'];

    if ($action_choisie == 'valider') {
        $update = $bdd->prepare("UPDATE annonces SET statut = 'validee' WHERE id = ?");
        $update->execute([$id_annonce]);
    }

    if ($action_choisie == 'modifier') {
        $motif = $_POST['motif_modification'] ?? "";
        $update = $bdd->prepare("UPDATE annonces SET statut = 'en_attente', motif_modification = ? WHERE id = ?");
        $update->execute([$motif, $id_annonce]);
    }

    if ($action_choisie == 'refuser') {
        $motif = $_POST['motif_refus'] ?? "";
        $update = $bdd->prepare("UPDATE annonces SET statut = 'refusee', motif_refus = ? WHERE id = ?");
        $update->execute([$motif, $id_annonce]);
    }
}

# Pour récupérer les infos ( Stats )

$reqUsers = $bdd->query("SELECT COUNT(*) FROM users");
$totalUsers = $reqUsers->fetchColumn();

# Le total des annonces :

$reqAnnoncesStats = $bdd->query("SELECT COUNT(*) FROM annonces");
$totalAnnonces = $reqAnnoncesStats->fetchColumn();

# Pour les annonces en attentes ( Stast )

$reqAttenteStats = $bdd->query("SELECT COUNT(*) FROM annonces WHERE statut = 'en_attente'");
$totalAttente = $reqAttenteStats->fetchColumn();

# On récupère les annonces en attente :

$requete = $bdd->query("
    SELECT annonces.*, users.prenom, users.nom 
    FROM annonces 
    JOIN users ON annonces.user_id = users.id 
    WHERE annonces.statut = 'en_attente' 
    ORDER BY annonces.creation_date ASC
    LIMIT 1
");
$annonce = $requete->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="dashboard-layout">
        <div class="zone-annonces">
            <div class="moderation-container-admin">
                <div class="content-post">
                    <div class="gauche-post">
                        <div class="title-post">
                            <h2><?php echo ($annonce['titre']); ?></h2>
                        </div>
                        <div class="user-post">
                            <p>Publié par : <span><?php echo ($annonce['prenom'] . ' ' . $annonce['nom']); ?></span></p>
                        </div>
                        <div class="image-post">
                            <?php $photos = explode(',', $annonce['url_photo']); ?>
                            <img src="<?php echo ($photos[0]); ?>">
                        </div>
                    </div>
                    <div class="droite-post">
                        <div class="encadre-infos">
                            <div class="ligne-info">
                                <h4>DESCRIPTION : </h4>
                                <label><?php echo (($annonce['description'])); ?></label>
                            </div>
                            <div class="ligne-info">
                                <h4>CATEGORIE : </h4>
                                <label><?php echo ($annonce['categorie']); ?></label>
                            </div>
                            <div class="ligne-info">
                                <h4>SOUS-CATEGORIE : </h4>
                                <label><?php echo ($annonce['sous_categorie']); ?></label>
                            </div>
                            <div class="ligne-info">
                                <h4>VILLE : </h4>
                                <label><?php echo ($annonce['ville']); ?></label>
                            </div>
                            <div class="ligne-info">
                                <h4>ETAT : </h4>
                                <label><?php echo ($annonce['etat']); ?></label>
                            </div>
                            <div class="ligne-info">
                                <h4>PRIX : </h4>
                                <label><?php echo ($annonce['prix']); ?></label>
                                <h4>€</h4>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="buttons-container-admin">
                    <form method="POST" class="form-action-admin">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <input type="hidden" name="action" value="valider">
                        <button type="submit" class="btn-valider-admin">Valider</button>
                    </form>
                    <form method="POST" class="form-action-admin">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <input type="hidden" name="action" value="modifier">
                        <input type="text" name="motif_modification" placeholder="Modifications à prévoir">
                        <button type="submit" class="btn-modifier-admin">Modifier</button>
                    </form>
                    <form method="POST" class="form-action-admin">
                        <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                        <input type="hidden" name="action" value="refuser">
                        <input type="text" name="motif_refus" placeholder="Motif du refus">
                        <button type="submit" class="btn-refuser-admin">Refuser</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="zone-stats">
            <h1>Statistiques</h1>
            <p>Total Utilisateurs : <strong><?php echo $totalUsers; ?></strong></p>
            <p>Total Annonces : <strong><?php echo $totalAnnonces; ?></strong></p>
            <p>Annonces en attente : <strong><?php echo $totalAttente; ?></strong></p>
        </div>
        <div class="zone-menu-admin">
            <div class="encadre-menu">
                <h3>Gestion admin</h3>
                <a href="dashboard_admin.php" class="btn-menu-admin">Modération Annonces</a>
                <?php if (est_admin() === 'administrateur'): ?>
                    <a href="gestion_personnes.php" class="btn-menu-admin inactif">Gestion personne</a>
                    <a href=" visualisation_posts.php" class="btn-menu-admin inactif">Visualisation de tous les posts</a>
                    <a href="graphes_stats.php" class="btn-menu-admin inactif">Evolution des statistiques</a>
                    <a href="gestion_categories.php" class="btn-menu-admin inactif">Gestion catégories</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>