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
                <a href="index.php" style="text-decoration:none">Accueil</a>
                <a href="login_web.php" style="text-decoration:none">Connexion</a>
                <a href="inscription_web.php" style="text-decoration:none" class="styled"><button>Inscription</button></a>
            </div>
        </nav>
    </header>
    <div class="Inscription">
        <?php if ($errorConnection ?? false) {
            echo "Erreur d'identifiants";
        } ?>
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