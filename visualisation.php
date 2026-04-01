<?php
require_once 'datab_web.php';
require_once 'includes/navbar.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /index.php');
    exit;
}

$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = ?");
$stmt->execute([$id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    header('Location: /index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title><?= htmlspecialchars($annonce['titre']) ?></title>
    <link rel="stylesheet" href="style_web.css" />
</head>
<body>

<div class="conteneur-visu">
    <div class="posts-contenu">

        <div class="titre">
            <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
        </div>

        <div class="imagesannonces">
            <img src="<?= htmlspecialchars($annonce['url_photo']) ?>" alt="">
        </div>

        <div class="bio">
            <p>Description : <?= htmlspecialchars($annonce['description']) ?></p>
            <p>Prix : <?= htmlspecialchars($annonce['prix']) ?>€</p>
            <p>État : <?= htmlspecialchars($annonce['etat']) ?></p>
            <p>Catégorie : <?= htmlspecialchars($annonce['categorie']) ?></p>
            <p>Ville : <?= htmlspecialchars($annonce['ville']) ?></p>
            <p>Statut : <?= htmlspecialchars($annonce['statut']) ?></p>
            <div class="visu-bouton">
            <button>Ajouter aux favoris</button>
        </div>
        </div>

        <div style="clear: both;"></div>


    </div>
</div>

</body>
</html>