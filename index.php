<?php require_once 'datab_web.php';
if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div style="color: #a70000; width: fit-content; margin-left: auto; margin-right: auto; margin-top: 200px; text-align: center; border-radius: 5px;">
        Votre annonce a bien été publiée !
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Accueil</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <?php require_once 'includes/catalog.php'; ?>
    <main class="container-site">
        <section class="topic">
            <h1>TechMarket</h1>
            <p>plateforme d'achat et de revente de vos objets high-tech préférés</p>
        </section>
        <section class="bouton-temporaire">
            <a href="publication_web.php">Publie ton annonce</a>
            <a href="publication_web_ia.php">Publie ton annonce avec l'IA</a>
        </section>
    </main>
    <footer class="main-footer">
        <div class="container-site">
            <div class="head-footer">
                <h3 style=" display: flex; justify-content: flex-start;">TechMarket</h3>
                <p style=" display: flex; justify-content: flex-start;">La plateforme la plus sûr pour acheter et revendre du matériel high-tech d'occasion.</p>
            </div>
            <div class="buttom-footer">
                <p style="font-weight: 100; font-size:smaller">© 2026 TechMarket. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>

</html>