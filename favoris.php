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
    JOIN categories ON annonces.categorie = categories.id
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style_web.css">
</head>

<body>

    <?php require_once 'includes/navbar.php'; ?>

    <?php if (empty($favoris)): ?>
        <div class="annonces">
            <p>Vous n'avez pas encore d'annonces en favoris.</p>
            <a href="index.php">Parcourir les annonces</a>
        </div>
    <?php else: ?>
        <div class="blocs_annonces">
            <h1 class="Titre" style="color: #5D3FD3;"> Mes Favoris (<?= count($favoris) ?>)</h1>
            <?php foreach ($favoris as $fav): ?>
                <div class="annonces">
                    <div class="titre">
                        <h2><?= htmlspecialchars($fav['titre']) ?></h2>
                        <?php
                        $photos = explode(',', $fav['url_photo']);
                        ?>
                        <img src="<?= htmlspecialchars($photos[0]) ?>" alt="Photo de l'annonce" class="imagesannonces">
                    </div>
                    <div class="bio">
                        <p>Prix : <?= htmlspecialchars($fav['prix']) ?> €</p>
                        <p>Catégorie : <?= htmlspecialchars($fav['categorie']) ?></p>
                        <p>Ville : <?= htmlspecialchars($fav['ville']) ?></p>
                        <p>Vendeur : <?= htmlspecialchars($fav['vendeur_prenom']) ?> <?= htmlspecialchars($fav['vendeur_nom']) ?></p>
                    </div>
                    <div>
                        <a href="supp_favoris.php?id=<?= $fav['id'] ?>" class="bouton"> ❤️ Retirer</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>

</html>