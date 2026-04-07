<?php
require_once 'datab_web.php';


if (!isset($_SESSION['userid'])) {
    header('Location: login_web.php');
    exit;
}

$pdo = db();
$userId = $_SESSION['userid'];


$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$stmtAnnonces = $pdo->prepare("
    SELECT 
        annonces.*,
        c1.nom AS categorie_nom,
        c2.nom AS sous_categorie_nom
    FROM annonces
    LEFT JOIN categories AS c1 ON annonces.categorie = c1.id
    LEFT JOIN categories AS c2 ON annonces.sous_categorie = c2.id
    WHERE annonces.user_id = ?
    ORDER BY annonces.creation_date DESC
");
$stmtAnnonces->execute([$userId]);
$annonces = $stmtAnnonces->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <section class="bouton-temporaire">
        <a href="publication_web_ia.php" class="button">Publie ton annonce</a>
    </section>
    <div class="">
        <h1 style="margin-top: 5vh; color: #5D3FD3;">Mes annonces (<?= count($annonces) ?>)</h1>
        <div class="infos_annonces">

            <?php if (empty($annonces)): ?>
                <div class="infos_annonces_container">
                    <p>Tu n'as pas encore d'annonces.</p>
                </div>
            <?php else: ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="infos_annonces_container">
                        <img src="<?= htmlspecialchars($annonce['url_photo']) ?>" alt="photo annonce" width=350px height=300px>
                        <div>
                            <h3><?= htmlspecialchars($annonce['titre']) ?></h3>
                            <p>Catégorie : <?= htmlspecialchars($annonce['categorie_nom'] ?? 'Non définie') ?></p>
                            <p>Sous-catégorie : <?= htmlspecialchars($annonce['sous_categorie_nom'] ?? 'Non définie') ?></p>
                            <p>Ville : <?= htmlspecialchars($annonce['ville']) ?></p>
                            <p>Description : <?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...</p>
                            <p>Prix : <?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
                            <p>Statut : <?= ucfirst($annonce['statut']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>