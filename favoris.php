<?php
require_once 'datab_web.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$pdo = db();
$userId = $_SESSION['userid'];

$stmt = $pdo->prepare("
    SELECT 
        annonces.id,
        annonces.titre,
        annonces.prix,
        annonces.ville,
        annonces.statut,
        annonces.url_photo,
        categories.nom AS categorie,
        users.nom AS vendeur_nom,
        users.prenom AS vendeur_prenom
    FROM favoris
    JOIN annonces ON favoris.id_annonce = annonces.id
    JOIN users ON annonces.user_id = users.id
    JOIN categories ON annonces.categorie_id = categories.id
    WHERE favoris.id_user = ?
    ORDER BY favoris.id DESC
");
$stmt->execute([$userId]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="style_web.css">
</head>

<body>

    <?php require_once 'includes/navbar.php'; ?>

    <div class="infos_favoris">
        <h1>Mes favoris (<?= count($favoris) ?>)</h1>

        <?php if (empty($favoris)): ?>
            <div class="infos_favoris_container">
                <p>Tu n'as pas encore d'annonces en favoris.</p>
                <a href="index.php">Parcourir les annonces</a>
            </div>
        <?php else: ?>
            <div class="infos_favoris_container">
                <?php foreach ($favoris as $fav): ?>
                    <div class="favori-card">
                        <img src="<?= htmlspecialchars($fav['url_photo']) ?>" alt="photo annonce">
                        <div class="favori-info">
                            <h3><?= htmlspecialchars($fav['titre']) ?></h3>
                            <p class="categorie"> <?= htmlspecialchars($fav['categorie']) ?></p>
                            <p class="vendeur"> <?= htmlspecialchars($fav['vendeur_prenom']) ?> <?= htmlspecialchars($fav['vendeur_nom']) ?></p>
                            <p class="ville"> <?= htmlspecialchars($fav['ville']) ?></p>
                            <div class="favori-footer">
                                <span class="prix"><?= number_format($fav['prix'], 2, ',', ' ') ?> €</span>
                                <div class="favori-actions">
                                    <a href="annonce.php?id=<?= $fav['id'] ?>" class="bouton">Voir l'annonce</a>
                                    <a href="supp_favori.php?id=<?= $fav['id'] ?>" class="bouton"> Retirer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>