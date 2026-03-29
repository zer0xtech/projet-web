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


$stmtAnnonces = $pdo->prepare("SELECT * FROM annonces WHERE user_id = ? ORDER BY creation_date DESC");
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

    <div class="infos_profil">

        <div class="infos_profil_outer">
            <h1>Mon Profil</h1>
            <a class="" href="modification_profil.php"> Modifier mon profil </a>
            <div class="">
                <p>Nom : <span><?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?></span></p>
                <p>Email : <span><?= htmlspecialchars($user['email']) ?></span></p>
                <p>Téléphone : <span><?= htmlspecialchars($user['phone']) ?></span></p>
                <p>Ville : <span><?= htmlspecialchars($user['ville']) ?></span></p>
                <p>Inscrit depuis le : <span><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></span></p>
            </div>
        </div>
    </div>
</body>

</html>