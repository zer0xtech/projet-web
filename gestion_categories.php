<?php
require_once 'data/user_test.php';
$bdd = db();

if (est_admin() !== 'administrateur') {
    header('Location: dashboard_admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'ajouter_principale') {
        $nom = $_POST['nom_categorie'];
        $insert = $bdd->prepare("INSERT INTO categories (nom, parent_id) VALUES (?, NULL)");
        $insert->execute([$nom]);
    }

    if ($_POST['action'] === 'ajouter_sous_categorie') {
        $nom = $_POST['nom_sous_categorie'];
        $parent_id = $_POST['parent_id'];
        $insert = $bdd->prepare("INSERT INTO categories (nom, parent_id) VALUES (?, ?)");
        $insert->execute([$nom, $parent_id]);
    }

    if ($_POST['action'] === 'supprimer') {
        $id_cat = $_POST['categorie_id'];
        $deleteSub = $bdd->prepare("DELETE FROM categories WHERE parent_id = ?");
        $deleteSub->execute([$id_cat]);

        $delete = $bdd->prepare("DELETE FROM categories WHERE id = ?");
        $delete->execute([$id_cat]);
    }

    header('Location: gestion_categories.php');
    exit();
}


$reqPrincipales = $bdd->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY nom ASC");
$categories_principales = $reqPrincipales->fetchAll();

$reqSousCat = $bdd->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY nom ASC");
$sous_categories = $reqSousCat->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Gestion des Catégories</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>

    <div class="dashboard-layout">

        <div class="zone-menu-admin">
            <div class="encadre-menu">
                <h3>Gestion admin</h3>
                <a href="dashboard_admin.php" class="btn-menu-admin inactif">Modération Annonces</a>
                <a href="gestion_personnes.php" class="btn-menu-admin inactif">Gestion personne</a>
                <a href=" visualisation_posts.php" class="btn-menu-admin inactif">Visualisation de tous les posts</a>
                <a href="graphes_stats.php" class="btn-menu-admin inactif">Evolution des statistiques</a>
                <a href="gestion_categories.php" class="btn-menu-admin inactif">Gestion catégories</a>
            </div>
        </div>

        <div class="zone-contenu-admin">
            <h2>Gestion des Catégories</h2>

            <div class="forms-ajout-container">

                <form method="POST" class="form-ajout-categorie">
                    <h4>Nouvelle Catégorie Principale</h4>
                    <input type="hidden" name="action" value="ajouter_principale">
                    <input type="text" name="nom_categorie" placeholder="Ex: Informatique" required class="input-motif">
                    <button type="submit" class="btn-valider-admin">Ajouter</button>
                </form>

                <form method="POST" class="form-ajout-categorie">
                    <h4>Nouvelle Sous-Catégorie</h4>
                    <input type="hidden" name="action" value="ajouter_sous_categorie">

                    <select name="parent_id" class="select-role" required>
                        <option value="">Sélectionner la catégorie parente...</option>
                        <?php foreach ($categories_principales as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom']); ?></option>
                        <?php } ?>
                    </select>

                    <input type="text" name="nom_sous_categorie" placeholder="Ex: Ordinateurs portables" required class="input-motif">
                    <button type="submit" class="btn-valider-admin">Ajouter</button>
                </form>
            </div>

            <table class="table-users">
                <thead>
                    <tr>
                        <th>Catégorie Principale</th>
                        <th>Sous-catégories associées</th>
                        <th>Action Principale</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories_principales as $catPrincipale) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($catPrincipale['nom']); ?></strong></td>

                            <td>
                                <ul class="liste-sous-categories">
                                    <?php
                                    $aDesSousCategories = false;
                                    foreach ($sous_categories as $sub) {
                                        if ($sub['parent_id'] == $catPrincipale['id']) {
                                            $aDesSousCategories = true;
                                    ?>
                                            <li class="item-sous-categorie">
                                                <?php echo htmlspecialchars($sub['nom']); ?>
                                                <form method="POST" class="form-inline">
                                                    <input type="hidden" name="action" value="supprimer">
                                                    <input type="hidden" name="categorie_id" value="<?php echo $sub['id']; ?>">
                                                    <button type="submit" class="btn-icon delete" title="Supprimer la sous-catégorie">
                                                        <span class="material-symbols-outlined icon-small">delete</span>
                                                    </button>
                                                </form>
                                            </li>
                                    <?php
                                        }
                                    }
                                    if (!$aDesSousCategories) {
                                        echo "<span class='text-muted'>Aucune</span>";
                                    }
                                    ?>
                                </ul>
                            </td>

                            <td class="text-center">
                                <form method="POST" class="form-inline">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="categorie_id" value="<?php echo $catPrincipale['id']; ?>">
                                    <button type="submit" class="btn-icon delete" title="Supprimer la catégorie principale (et toutes ses sous-catégories)">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>