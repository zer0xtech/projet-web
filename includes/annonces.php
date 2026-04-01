<?php
$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE statut = 'validee' ORDER BY creation_date DESC");
$stmt->execute();
$annonces = $stmt->fetchAll();
?>

<div class="blocs">
    <?php foreach ($annonces as $annonce): ?>
        <div class="annonces">
            <div class="titre">
                <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
                <a href="/visualisation.php?id=<?= $annonce['id'] ?>">
                    <img src="<?= htmlspecialchars($annonce['url_photo']) ?>" alt="" class="imagesannonces">
                </a>
            </div>
            <div class="bio">
                <p>Prix : <?= htmlspecialchars($annonce['prix']) ?>€</p>
                <p>État : <?= htmlspecialchars($annonce['etat']) ?></p>
                <p>Ville : <?= htmlspecialchars($annonce['ville']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>