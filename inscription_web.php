<?php
require_once 'data/user_test.php';
$inscription = inscription();
if ($inscription === true) {
    header('Location: login_web.php?pseudo=' . $_POST['username']);
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
    <link rel="stylesheet" href="inscription_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div class="Inscription">
        <?php if (is_string($inscription)):
            echo '<p class="error">' . $inscription . '</p>';
        endif; ?>
        <form method="POST" class="connexion">
            <h2>Inscription</h2>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <label for="ConfirmPassword">Confirmez le Password</label>
            <input type="password" name="ConfirmPassword" id="ConfirmPassword">
            <div class="action">
                <input type="submit" class="bouton" value="S'inscrire">
            </div>
        </form>
    </div>

</body>

</html>