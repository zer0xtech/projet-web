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
    <title>login</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <?php require_once 'includes/catalog.php'; ?>
    <a href="publication_web.php" class="bouton-temporaire">Publie ton annonce</a>
</body>

</html>