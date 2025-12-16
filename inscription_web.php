<?php
require_once 'data/user_test.php';
$inscription = inscription();
if ($inscription === true) {
    header('Location: login_web.php?pseudo=' . $_POST['email']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>login</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="Inscription">
        <?php if (is_string($inscription)):
            echo '<p class="error">' . $inscription . '</p>';
        endif; ?>
        <form method="POST" class="connexion">
            <h2>Inscription</h2>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" />
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" />
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" />
            <label for="telephone">Téléphone</label>
            <input type="telephone" name="telephone" id="telephone" />
            <label for="password">Mot de Passe</label>
            <input type="password" name="password" id="password" />
            <label for="ConfirmPassword">Confirmez le Mot de Passe</label>
            <input type="password" name="ConfirmPassword" id="ConfirmPassword" />
            <div class="action">
                <input type="submit" class="bouton" value="S'inscrire">
            </div>
        </form>
    </div>

</body>

</html>