<?php
require_once 'datab_web.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login_web.php');
    exit;
}

$pdo = db();
$userId = $_SESSION['userid'];
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $phone = $_POST['phone'];
    $ville = $_POST['ville'];
    $email = $_POST['email'];

    $verifEmail = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
    $verifEmail->execute([$email, $userId]);
    if ($verifEmail->fetchColumn() > 0) {
        $message = "Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, phone = ?, ville = ?, email = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $phone, $ville, $email, $userId]);
        $_SESSION['email'] = $email;
        $message = "Profil mis à jour avec succès.";
    }
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="style_web.css">
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="infos_profil">
        <h1>Modifier mon profil</h1>
        <?php if ($message): ?>
            <p class="message <?= str_contains($message, 'succès') ? 'succes' : 'erreur' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
        <form class="infos_modifs_profil" method="POST" action="">
            <div class="details_modifs_profil">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>
            <div class="details_modifs_profil">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>
            <div class="details_modifs_profil">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="details_modifs_profil">
                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <div class="details_modifs_profil">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($user['ville']) ?>" required>
            </div>
            <div class="details_modifs_profil">
                <label for="password">Nouveau mot de passe <small>(laisser vide pour ne pas changer)</small></label>
                <input type="password" id="password" name="password">
            </div>
            <div class="details_modifs_profil">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <div class="bouton_modifs_profil">
                <button type="submit">Enregistrer</button>
                <div><a class="" href="consultation_profil.php">Annuler</a></div>
            </div>
        </form>
    </div>
</body>

</html>