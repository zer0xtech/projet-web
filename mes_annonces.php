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
        categories.nom AS categorie_nom
    FROM annonces
    JOIN categories ON annonces.categorie = categories.id
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

    <div class="">
        <div class="infos_annonces">
            <h1>Mes annonces (<?= count($annonces) ?>)</h2>
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
                                <p>Sous-catégorie : <?= htmlspecialchars($annonce['categorie_nom']) ?> — <?= htmlspecialchars($annonce['ville']) ?> (Ville)</p>
                                <p>Description : <?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...</p>
                                <div>
                                    <span class="prix"> Prix : <?= number_format($annonce['prix'], 2, ',', ' ') ?> €</span>
                                    <br>
                                    <span class="statut statut-<?= $annonce['statut'] ?>">Statut : <?= ucfirst($annonce['statut']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
        </div>
    </div>
</body>

</html>