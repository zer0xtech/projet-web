<?php
require_once 'data/user_test.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>login</title>
    <link rel="stylesheet" href="login_web.css" />
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="gauche">
                <a href="index.php"><img src="./images/TechMarket.png" alt="logo" class="logo" height="auto" width="100"></a>
                <div class="gauche-recherche">
                    <input type="search" placeholder="  recherche" class="recherche">
                    <span class="material-symbols-outlined">search</span>
                </div>
            </div>
            <div class="droite">
                <?php if (estConnecte()): ?>
                    <a href="index.php">Acceuil</a>
                    <a href="/">Mon Profil</a>
                    <a href="/">Mes Annonces</a>
                    <a href="logout_web.php">Déconnexion</a>
                <?php else: ?>
                    <a href="index.php">Acceuil</a>
                    <a href="login_web.php">Connexion</a>
                    <a href="inscription.php">Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <h1>ACCEUIL</h1>
</body>

</html>