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
    <div class="login-wrapper2">
        <img src="images/TechMarket (1)_preview_rev_1.png" class="reveal-title2">
        <div class="Inscription">
            <?php if (is_string($inscription)):
                echo '<p class="error">' . $inscription . '</p>';
            endif; ?>
            <form method="POST" class="connexion">
                <div style="display: flex; flex-direction:column; gap:1vh">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" />
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" />
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" />
                    <label for="telephone">Téléphone</label>
                    <input type="telephone" name="telephone" id="telephone" />
                </div>
                <div style="display: flex; flex-direction:column; gap:1vh">
                    <label for="ville">Ville</label>
                    <input type="ville" name="ville" id="ville" />
                    <label for="password">Mot de Passe</label>
                    <input type="password" name="password" id="password" />
                    <label for="ConfirmPassword">Confirmez le Mot de Passe</label>
                    <input type="password" name="ConfirmPassword" id="ConfirmPassword" />
                    <div class="action">
                        <input type="submit" class="bouton" value="S'inscrire">
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>