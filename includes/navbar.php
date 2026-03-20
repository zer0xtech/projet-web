<?php
require_once 'data/user_test.php';
?>

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
                <a href="logout_web.php" class="button">Déconnexion</a>
            <?php else: ?>
                <a href="index.php">Acceuil</a>
                <a href="login_web.php">Connexion</a>
                <a href="inscription_web.php" class="button">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>
</header>