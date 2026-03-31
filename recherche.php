<?php
require_once 'datab_web.php';
require_once 'includes/navbar.php';

$q = $_GET['q'] ?? '';
$categorie = $_GET['categorie'] ?? '';
$sous_categorie = $_GET['sous_categorie'] ?? '';
$prix_min = $_GET['prix_min'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$ville = $_GET['ville'] ?? '';
$etat = $_GET['etat'] ?? '';
$tri = $_GET['tri'] ?? 'date_desc';

$sql = "SELECT * FROM annonces WHERE statut = 'validee'";
$params = [];

if ($q) {
    $sql .= " AND (titre LIKE ? OR description LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}
if ($categorie) {
    $sql .= " AND categorie = ?";
    $params[] = $categorie;
}
if ($sous_categorie) {
    $sql .= " AND sous_categorie = ?";
    $params[] = $sous_categorie;
}
if ($prix_min) {
    $sql .= " AND prix >= ?";
    $params[] = $prix_min;
}
if ($prix_max) {
    $sql .= " AND prix <= ?";
    $params[] = $prix_max;
}
if ($ville) {
    $sql .= " AND ville LIKE ?";
    $params[] = "%$ville%";
}
if ($etat) {
    $sql .= " AND etat = ?";
    $params[] = $etat;
}

switch ($tri) {
    case 'prix_asc':  $sql .= " ORDER BY prix ASC"; break;
    case 'prix_desc': $sql .= " ORDER BY prix DESC"; break;
    case 'date_asc':  $sql .= " ORDER BY creation_date ASC"; break;
    default:          $sql .= " ORDER BY creation_date DESC"; break;
}


$pdo = db();
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Recherche — TechMarket</title>
    <link rel="stylesheet" href="style_web.css" />
</head>
<body>

<!-- HERO -->
<div class="hero">
    <h1>TechMarket</h1>
    <p>La plateforme de petites annonces pour votre matériel high-tech d'occasion</p>
</div>

<!-- BLOC RECHERCHE -->
<div class="bloc-recherche">
    <form method="GET" action="/recherche.php">

        <div class="recherche-principale">
            <input type="search" name="q" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($q) ?>">
            <button type="submit">Rechercher</button>
        </div>

        <div class="filtres-ligne">
            <select name="categorie">
                <option value="">Toutes les catégories</option>
                <option value="Informatique" <?= $categorie == 'Informatique' ? 'selected' : '' ?>>Informatique</option>
                <option value="Téléphone" <?= $categorie == 'Téléphone' ? 'selected' : '' ?>>Téléphone</option>
                <option value="Audio/Vidéo" <?= $categorie == 'Audio/Vidéo' ? 'selected' : '' ?>>Audio/Vidéo</option>
            </select>

            <select name="sous_categorie">
                <option value="">Toutes les sous-catégories</option>
                <option value="Portables" <?= $sous_categorie == 'Portables' ? 'selected' : '' ?>>Portables</option>
                <option value="Fixes" <?= $sous_categorie == 'Fixes' ? 'selected' : '' ?>>Fixes</option>
                <option value="Gaming" <?= $sous_categorie == 'Gaming' ? 'selected' : '' ?>>Gaming</option>
                <option value="Cartes graphiques" <?= $sous_categorie == 'Cartes graphiques' ? 'selected' : '' ?>>Cartes graphiques</option>
                <option value="Processeurs" <?= $sous_categorie == 'Processeurs' ? 'selected' : '' ?>>Processeurs</option>
                <option value="Carte mère" <?= $sous_categorie == 'Carte mère' ? 'selected' : '' ?>>Carte mère</option>
                <option value="Souris" <?= $sous_categorie == 'Souris' ? 'selected' : '' ?>>Souris</option>
                <option value="Claviers" <?= $sous_categorie == 'Claviers' ? 'selected' : '' ?>>Claviers</option>
                <option value="Écrans" <?= $sous_categorie == 'Écrans' ? 'selected' : '' ?>>Écrans</option>
                <option value="Smartphones" <?= $sous_categorie == 'Smartphones' ? 'selected' : '' ?>>Smartphones</option>
                <option value="Coques" <?= $sous_categorie == 'Coques' ? 'selected' : '' ?>>Coques</option>
                <option value="Chargeurs" <?= $sous_categorie == 'Chargeurs' ? 'selected' : '' ?>>Chargeurs</option>
                <option value="Casques" <?= $sous_categorie == 'Casques' ? 'selected' : '' ?>>Casques</option>
                <option value="Enceintes" <?= $sous_categorie == 'Enceintes' ? 'selected' : '' ?>>Enceintes</option>
                <option value="Caméras" <?= $sous_categorie == 'Caméras' ? 'selected' : '' ?>>Caméras</option>
            </select>

            <input type="number" name="prix_min" placeholder="Prix min" value="<?= htmlspecialchars($prix_min) ?>">
            <input type="number" name="prix_max" placeholder="Prix max" value="<?= htmlspecialchars($prix_max) ?>">
            <input type="text" name="ville" placeholder="Ville" value="<?= htmlspecialchars($ville) ?>">

            <select name="etat">
                <option value="">Tous les états</option>
                <option value="neuf" <?= $etat == 'neuf' ? 'selected' : '' ?>>Neuf</option>
                <option value="tres_bon_etat" <?= $etat == 'tres_bon_etat' ? 'selected' : '' ?>>Très bon état</option>
                <option value="bon_etat" <?= $etat == 'bon_etat' ? 'selected' : '' ?>>Bon état</option>
                <option value="etat_correct" <?= $etat == 'etat_correct' ? 'selected' : '' ?>>État correct</option>
            </select>
        </div>

        <div class="filtres-ligne">
            <select name="tri" style="flex:1">
                <option value="date_desc" <?= $tri == 'date_desc' ? 'selected' : '' ?>>Plus récentes</option>
                <option value="date_asc" <?= $tri == 'date_asc' ? 'selected' : '' ?>>Plus anciennes</option>
                <option value="prix_asc" <?= $tri == 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
                <option value="prix_desc" <?= $tri == 'prix_desc' ? 'selected' : '' ?>>Prix décroissant</option>
            </select>
            <button type="submit">Filtrer</button>
            <a href="/index.php" class="btn-reinit">Réinitialiser</a>
        </div>

    </form>
</div>

<!-- RÉSULTATS -->
<h2 class="titre-section">
    <?= $q ? 'Résultats pour "' . htmlspecialchars($q) . '"' : ($categorie ? htmlspecialchars($categorie) : 'Annonces récentes') ?>
</h2>

<div class="blocs">
    <?php if (empty($resultats)): ?>
        <p style="padding: 20px;">Aucune annonce trouvée.</p>
    <?php else: ?>
        <?php foreach ($resultats as $annonce): ?>
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
    <?php endif; ?>
</div>

</body>
</html>