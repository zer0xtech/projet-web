<?php
require_once 'datab_web.php';
$pdo = db();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h2>Annonce introuvable.</h2>");
}

$id_annonce = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT 
        annonces.*, 
        users.prenom, users.email, users.phone,
        c1.nom AS categorie_nom, 
        c2.nom AS sous_categorie_nom
    FROM annonces
    JOIN users ON annonces.user_id = users.id
    LEFT JOIN categories AS c1 ON annonces.categorie = c1.id
    LEFT JOIN categories AS c2 ON annonces.sous_categorie = c2.id
    WHERE annonces.id = ?
");
$stmt->execute([$id_annonce]);
$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    die("<h2>Cette annonce n'existe pas ou a été supprimée.</h2>");
}

$photos = explode(',', $annonce['url_photo']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title><?= htmlspecialchars($annonce['titre']) ?> — TechMarket</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>

    <div class="annonce-detail-container">

        <div class="annonce-images">
            <?php foreach ($photos as $photo): ?>
                <?php if (!empty(trim($photo))): ?>
                    <img src="<?= htmlspecialchars(trim($photo)) ?>" alt="Photo de l'annonce">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="annonce-infos">
            <p class="annonce-categorie">
                <?= htmlspecialchars($annonce['categorie_nom']) ?> > <?= htmlspecialchars($annonce['sous_categorie_nom']) ?>
            </p>

            <h1><?= htmlspecialchars($annonce['titre']) ?></h1>

            <div class="annonce-prix"><?= htmlspecialchars($annonce['prix']) ?> €</div>

            <p><strong>État :</strong> <?= htmlspecialchars($annonce['etat']) ?></p>
            <p><strong>Lieu :</strong> <?= htmlspecialchars($annonce['ville']) ?></p>
            <p><strong>Publié le :</strong> <?= date('d/m/Y', strtotime($annonce['creation_date'])) ?></p>

            <hr>

            <h3>Description</h3>
            <p class="annonce-description"><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>

            <button class="btn-favoris">
                <span class="material-symbols-outlined">favorite</span>
                Ajouter aux favoris
            </button>

            <div class="vendeur-box">
                <h3>Contacter le vendeur</h3>
                <p><strong>Vendeur :</strong> <?= htmlspecialchars($annonce['prenom']) ?></p>
                <?php if (!empty($annonce['email'])): ?>
                    <p><strong>Email :</strong> <a href="mailto:<?= htmlspecialchars($annonce['email']) ?>"><?= htmlspecialchars($annonce['email']) ?></a></p>
                <?php endif; ?>
                <?php if (!empty($annonce['phone'])): ?>
                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($annonce['phone']) ?></p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</body>

</html>