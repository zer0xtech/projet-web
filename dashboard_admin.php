<?php

$pdo = db();
$id = $_GET['id'];

if (isset($_POST['action'])) {

    if ($_POST['action'] == 'valider') {
        $update = $pdo->prepare("UPDATE annonces SET statut = 'validee' WHERE id = ?");
        $update->execute([$id]);
        header('Location: moderateur.php');
        exit();
    }

    if ($_POST['action'] == 'modifier') {
        $update = $pdo->prepare("UPDATE annonces SET statut = 'validee', titre = ?, description = ?, motif_modification = ? WHERE id = ?");
        $update->execute([$_POST['titre'], $_POST['description'], $_POST['motif_modif'], $id]);
        header('Location: moderateur.php');
        exit();
    }

    if ($_POST['action'] == 'refuser') {
        $update = $pdo->prepare("UPDATE annonces SET statut = 'refusee', motif_refus = ? WHERE id = ?");
        $update->execute([$_POST['motif_refus'], $id]);
        header('Location: moderateur.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html>

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
    </div>
</body>

</html>