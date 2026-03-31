<?php
require_once 'data/user_test.php';
$bdd = db();

if (est_admin() !== 'administrateur') {
    header('Location: dashboard_admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'modifier_role') {
        $user_id = $_POST['user_id'];
        $nouveau_role = $_POST['role'];
        $update = $bdd->prepare("UPDATE users SET role = ? WHERE id = ?");
        $update->execute([$nouveau_role, $user_id]);
    }

    if ($_POST['action'] === 'bloquer') {
        $user_id = $_POST['user_id'];
        $update = $bdd->prepare("UPDATE users SET blocked = 1 WHERE id = ?");
        $update->execute([$user_id]);
    }

    if ($_POST['action'] === 'debloquer') {
        $user_id = $_POST['user_id'];
        $update = $bdd->prepare("UPDATE users SET blocked = 0 WHERE id = ?");
        $update->execute([$user_id]);
    }
    if ($_POST['action'] === 'supprimer') {
        $user_id = $_POST['user_id'];
        $deleteAnnonces = $bdd->prepare("DELETE FROM annonces WHERE user_id = ?");
        $deleteAnnonces->execute([$user_id]);
        $deleteUser = $bdd->prepare("DELETE FROM users WHERE id = ?");
        $deleteUser->execute([$user_id]);
    }
}

$requete = $bdd->query("SELECT * FROM users ORDER BY id ASC");
$utilisateurs = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Gestion des Utilisateurs</title>
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
            <h2>Gestion des Utilisateurs</h2>

            <table class="table-users">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $user) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['nom']); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>

                            <td>
                                <select name="role" class="select-role" form="form-role-<?php echo $user['id']; ?>">
                                    <option value="utilisateur" <?php if ($user['role'] == 'utilisateur') echo 'selected'; ?>>Utilisateur</option>
                                    <option value="moderateur" <?php if ($user['role'] == 'moderateur') echo 'selected'; ?>>Modérateur</option>
                                    <option value="administrateur" <?php if ($user['role'] == 'administrateur') echo 'selected'; ?>>Administrateur</option>
                                </select>
                            </td>

                            <td>
                                <div class="actions-cell">

                                    <form id="form-role-<?php echo $user['id']; ?>" method="POST" style="margin: 0;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="modifier_role">
                                        <button type="submit" class="btn-icon" title="Sauvegarder">
                                            <span class="material-symbols-outlined">save</span>
                                        </button>
                                    </form>

                                    <?php if ($user['blocked'] == 0) { ?>
                                        <form method="POST" style="margin: 0;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="bloquer">
                                            <button type="submit" class="btn-icon" title="Bloquer l'utilisateur">
                                                <span class="material-symbols-outlined">block</span>
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <form method="POST" style="margin: 0;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="debloquer">
                                            <button type="submit" class="btn-icon" title="Débloquer l'utilisateur" style="color: red;">
                                                <span class="material-symbols-outlined">lock_open</span>
                                            </button>
                                        </form>
                                    <?php } ?>

                                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <input type="hidden" name="action" value="supprimer">
                                        <button type="submit" class="btn-icon delete" title="Supprimer">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>