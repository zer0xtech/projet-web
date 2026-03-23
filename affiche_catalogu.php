<?php
require_once 'data/user_test.php';

$aff_ann = [];

if (isset($_GET['cat2']) ?? '') {
    $aff_ann = affiche_annonces();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <div style="padding-top: 200px; display: flex; flex-wrap: wrap; gap:50px; align-items: center; justify-content: center;">
        <?php if (empty($aff_ann)): ?>
            <div style="color: #a70000; width: fit-content; margin-left: auto; margin-right: auto; margin-top: 200px; text-align: center; border-radius: 5px;">
                Il n'y a pas d'annonces dans cette catégorie !
            </div>
        <?php else: ?>
            <?php foreach ($aff_ann as $annonce): ?>
                <?php if ($annonce['statut'] == 'en_attente'): ?>
                    <div style="color: #a70000; text-align: center; border-radius: 5px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);">
                        <p>Cette annonce est en attente de validation par l'équipe de modération !</p>
                    </div>
                <?php else: ?>
                    <div class="publi-container_catalg">
                        <h2 style="color: #5D3FD3;"><?= htmlspecialchars($annonce['titre']) ?></h2>
                        <div style="display:flex;">
                            <?php $all_photos = explode(',', $annonce['url_photo']);
                            foreach ($all_photos as $photo):
                                if (!empty($photo)): ?>
                                    <img src="<?= htmlspecialchars($photo) ?>" alt="photo annonce" style="max-width: 150px; margin-right: 10px;">
                            <?php endif;
                            endforeach; ?>
                        </div>
                        <div class="">
                            <div style="display: flex; justify-content:space-between">
                                <div style="display: flex; gap:10px">
                                    <p style="text-transform: uppercase;"><?= htmlspecialchars($annonce['categorie']) ?></p>
                                    <p>-</p>
                                    <p style="text-transform: uppercase;"><?= htmlspecialchars($annonce['sous_categorie']) ?></p>
                                </div>
                                <p><?= number_format($annonce['prix']) ?> €</p>
                            </div>
                            <p>État : <?= htmlspecialchars($annonce['etat']) ?></p>
                            <p>LIEU : <?= htmlspecialchars($annonce['ville']) ?></p>
                            <p>DESCRIPTION : <?= htmlspecialchars(substr($annonce['description'], 0, 100)) ?>...</p>
                            <p style="display:flex; justify-content: flex-end; margin-top:50px;"><?= htmlspecialchars($annonce['creation_date']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>