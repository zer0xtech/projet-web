<?php
require_once 'data/user_test.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {
    $errorConnection = false;
    if (login($_POST['username'], $_POST['password'])) {
        header('Location: index.php');
    } else {
        $errorConnection = true;
    }
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
    <?php require_once 'includes/navbar.php' ?>
    <div class="Connect">
        <?php if ($errorConnection ?? false) {
            echo "Erreur d'identifiants";
        } ?>
        <form method="POST" class="connexion">
            <h2>Connexion</h2>
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <div class="action">
                <input type="submit" class="bouton" value="Se connecter">
            </div>
        </form>
    </div>

</body>

</html>