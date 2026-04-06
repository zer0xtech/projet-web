<?php require_once 'datab_web.php';
$pdo = db();

$reqCat = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY nom ASC");
$categories_principales = $reqCat->fetchAll();

$reqSousCat = $pdo->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY nom ASC");
$sous_categories = $reqSousCat->fetchAll();

$q = $_GET['q'] ?? '';
$categorie = $_GET['categorie'] ?? '';
$sous_categorie = $_GET['sous_categorie'] ?? '';
$prix_min = $_GET['prix_min'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$ville = $_GET['ville'] ?? '';
$etat = $_GET['etat'] ?? '';
$tri = $_GET['tri'] ?? 'date_desc';

$nom_categorie_affichee = '';
if ($categorie) {
    foreach ($categories_principales as $c) {
        if ($c['id'] == $categorie) {
            $nom_categorie_affichee = $c['nom'];
            break;
        }
    }
}

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
    case 'prix_asc':
        $sql .= " ORDER BY prix ASC";
        break;
    case 'prix_desc':
        $sql .= " ORDER BY prix DESC";
        break;
    case 'date_asc':
        $sql .= " ORDER BY creation_date ASC";
        break;
    default:
        $sql .= " ORDER BY creation_date DESC";
        break;
}

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll();

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
    <main class="container-site">
        <section class="topic">
            <h1>TechMarket</h1>
            <p>plateforme d'achat et de revente de vos objets high-tech préférés</p>
        </section>
        <div class="bloc-recherche">
            <form method="GET" action="/recherche.php">
                <div class="recherche-principale">
                    <input type="search" name="q" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($q) ?>">
                    <button type="submit">Rechercher</button>
                </div>

                <div class="filtres-ligne">

                    <select name="categorie" id="filtre-categorie">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories_principales as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categorie == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="sous_categorie" id="filtre-sous-categorie">
                        <option value="">Toutes les sous-catégories</option>
                        <?php foreach ($sous_categories as $sub): ?>
                            <option value="<?= $sub['id'] ?>" data-parent="<?= $sub['parent_id'] ?>" <?= $sous_categorie == $sub['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sub['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="number" name="prix_min" placeholder="Prix min" min="0" value="<?= htmlspecialchars($prix_min) ?>">
                    <input type="number" name="prix_max" placeholder="Prix max" min="0" value="<?= htmlspecialchars($prix_max) ?>">
                    <input type="text" name="ville" placeholder="Ville" value="<?= htmlspecialchars($ville) ?>">

                    <select name="etat">
                        <option value="">Tous les états</option>
                        <option value="Neuf" <?= $etat == 'Neuf' ? 'selected' : '' ?>>Neuf</option>
                        <option value="Très bon" <?= $etat == 'Très bon' ? 'selected' : '' ?>>Très bon</option>
                        <option value="Bon" <?= $etat == 'Bon' ? 'selected' : '' ?>>Bon</option>
                        <option value="correct" <?= $etat == 'correct' ? 'selected' : '' ?>>Correct</option>
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
                    <a href="/recherche.php" class="btn-reinit">Réinitialiser</a>
                </div>

            </form>
        </div>

        <h2 class="titre-section">
            <?= $q ? 'Résultats pour "' . htmlspecialchars($q) . '"' : ($nom_categorie_affichee ? htmlspecialchars($nom_categorie_affichee) : 'Annonces récentes') ?>
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
                                <?php
                                $photos = explode(',', $annonce['url_photo']);
                                ?>
                                <img src="<?= htmlspecialchars($photos[0]) ?>" alt="Photo de l'annonce" class="imagesannonces">
                            </a>
                        </div>
                        <div class="bio">
                            <p>Prix : <?= htmlspecialchars($annonce['prix']) ?>€</p>
                            <p>État : <?= htmlspecialchars($annonce['etat']) ?></p>
                            <p>Ville : <?= htmlspecialchars($annonce['ville']) ?></p>
                        </div>
                        <div>
                            <?php if ($userId): ?>
                                <?php
                                $verifFav = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE id_annonce = ? AND id_user = ?");
                                $verifFav->execute([$annonce['id'], $userId]);
                                $estFavori = $verifFav->fetchColumn() > 0;
                                ?>
                                <?php if ($estFavori): ?>
                                    <a href="supp_favoris.php?id=<?= $annonce['id'] ?>" class="button">❤️ Retirer</a>
                                <?php else: ?>
                                    <a href="ajout_favoris.php?id=<?= $annonce['id'] ?>" class="button">🤍 Favoris</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login_web.php" class="btn-favori">🤍 Favoris</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <script>
            const selectCategorie = document.getElementById('filtre-categorie');
            const selectSousCategorie = document.getElementById('filtre-sous-categorie');
            const toutesLesSousCategories = Array.from(selectSousCategorie.options).filter(opt => opt.value !== "");

            function filtrerSousCategories() {
                const idCategorieChoisie = selectCategorie.value;
                const valeurActuelle = selectSousCategorie.value;

                selectSousCategorie.innerHTML = '<option value="">Toutes les sous-catégories</option>';

                toutesLesSousCategories.forEach(function(option) {
                    if (idCategorieChoisie === "" || option.getAttribute('data-parent') === idCategorieChoisie) {
                        selectSousCategorie.appendChild(option);
                    }
                });

                selectSousCategorie.value = valeurActuelle;
            }

            selectCategorie.addEventListener('change', filtrerSousCategories);
            filtrerSousCategories();
        </script>
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