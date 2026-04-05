<?php

require_once 'data/user_test.php';
require_once 'ia_moderation.php';
$bdd = db();

$rapports_ia = [];

if (est_admin() !== 'administrateur' && est_admin() !== 'moderateur') {
    header('Location: index.php');
    exit();
}

if (isset($_POST['action'])) {
    $action_choisie = $_POST['action'];
    $id_annonce = $_POST['annonce_id'];

    if ($action_choisie == 'analyser_ia') {
        $stmt_ia = $bdd->prepare("
            SELECT annonces.titre, annonces.description, annonces.prix, 
                   c1.nom AS cat_nom, c2.nom AS sous_cat_nom 
            FROM annonces 
            LEFT JOIN categories AS c1 ON annonces.categorie = c1.id 
            LEFT JOIN categories AS c2 ON annonces.sous_categorie = c2.id 
            WHERE annonces.id = ?
        ");
        $stmt_ia->execute([$id_annonce]);
        $annonce_a_analyser = $stmt_ia->fetch();

        $stmt_cats = $bdd->query("SELECT nom FROM categories WHERE parent_id IS NULL");
        $cats_principales = $stmt_cats->fetchAll(PDO::FETCH_COLUMN);

        $stmt_subcats = $bdd->query("SELECT nom FROM categories WHERE parent_id IS NOT NULL");
        $sous_cats = $stmt_subcats->fetchAll(PDO::FETCH_COLUMN);

        if ($annonce_a_analyser) {
            $rapports_ia[$id_annonce] = analyserAnnonceAvecOllama(
                $annonce_a_analyser['titre'],
                $annonce_a_analyser['description'],
                $annonce_a_analyser['prix'],
                $annonce_a_analyser['cat_nom'],
                $annonce_a_analyser['sous_cat_nom'],
                $cats_principales,
                $sous_cats
            );
        }
    }

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

$reqUsers = $bdd->query("SELECT COUNT(*) FROM users");
$totalUsers = $reqUsers->fetchColumn();

$reqAnnoncesStats = $bdd->query("SELECT COUNT(*) FROM annonces");
$totalAnnonces = $reqAnnoncesStats->fetchColumn();

$reqAttenteStats = $bdd->query("SELECT COUNT(*) FROM annonces WHERE statut = 'en_attente'");
$totalAttente = $reqAttenteStats->fetchColumn();

$requete = $bdd->query("
    SELECT annonces.*, users.prenom, users.nom, 
           c1.nom AS nom_cat_principale, 
           c2.nom AS nom_sous_cat
    FROM annonces 
    JOIN users ON annonces.user_id = users.id 
    LEFT JOIN categories AS c1 ON annonces.categorie = c1.id
    LEFT JOIN categories AS c2 ON annonces.sous_categorie = c2.id
    WHERE annonces.statut = 'en_attente' 
    ORDER BY annonces.creation_date ASC
    LIMIT 1
");
$annonce = $requete->fetch();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="dashboard-layout">
        <div class="zone-annonces">
            <?php if ($annonce): ?>
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
                                <img src="<?php echo ($photos[0]); ?>" alt="Photo annonce">
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
                                    <label><?php echo ($annonce['nom_cat_principale'] ?? 'Inconnue'); ?></label>
                                </div>
                                <div class="ligne-info">
                                    <h4>SOUS-CATEGORIE : </h4>
                                    <label><?php echo ($annonce['nom_sous_cat'] ?? 'Inconnue'); ?></label>
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

                    <?php if (isset($rapports_ia[$annonce['id']])): ?>
                        <?php $analyse = $rapports_ia[$annonce['id']]; ?>
                        <div class="rapport-ia-container">
                            <h3>Rapport de Modération IA</h3>

                            <?php if ($analyse['success'] === false): ?>
                                <p class="erreur-ia">Erreur : <?= ($analyse['message']) ?></p>
                            <?php else: $rapport = $analyse['data']; ?>
                                <p><strong>Décision recommandée :</strong> <?= ($rapport['decision_recommandee']) ?></p>
                                <p><strong>Niveau de confiance :</strong> <?= ($rapport['niveau_confiance']) ?></p>
                                <p><strong>Catégorisation suggérée :</strong> <?= ($rapport['categorie_suggeree']) ?> > <?= ($rapport['sous_categorie_suggeree']) ?></p>
                                <p><strong>Suggestions de correction :</strong></p>
                                <ul class="liste-suggestions-ia">
                                    <?php if (empty($rapport['suggestions_correction'])): ?>
                                        <li>Aucune suggestion.</li>
                                    <?php else: ?>
                                        <?php foreach ($rapport['suggestions_correction'] as $sugg): ?>
                                            <li><?= htmlspecialchars($sugg) ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="buttons-container-admin">
                        <form method="POST" class="form-action-admin">
                            <input type="hidden" name="annonce_id" value="<?php echo $annonce['id']; ?>">
                            <input type="hidden" name="action" value="analyser_ia">
                            <button type="submit" class="btn-analyser-ia">Analyser avec l'IA</button>
                        </form>

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
            <?php else: ?>
                <div class="moderation-container-admin">
                    <h2>Aucune annonce en attente</h2>
                    <p>Aucune annonce à modérer.</p>
                </div>
            <?php endif; ?>
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