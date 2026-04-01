<?php
require_once 'data/user_test.php';
$bdd = db();

// check si le user est admin ou pas
if (est_admin() !== 'administrateur') {
    header('Location: dashboard_admin.php');
    exit();
}

// création du graphe depuis 30j
$stats = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stats[$date] = ['annonces' => 0, 'users' => 0];
}

// graphe annonces
$qA = $bdd->query("
    SELECT DATE(creation_date) as j, COUNT(*) as tot 
    FROM annonces 
    WHERE creation_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
    GROUP BY j");

// remplace les 0 du graphe par les valeurs
while ($ligne = $qA->fetch()) {
    $dateDuJour = $ligne['j'];
    $nombreAnnonces = $ligne['tot'];

    $stats[$dateDuJour]['annonces'] = (int)$nombreAnnonces;
}

// graphe users
$qU = $bdd->query("
    SELECT DATE(date_inscription) as j, COUNT(*) as tot 
    FROM users 
    WHERE date_inscription >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
    GROUP BY j");

// remplace les 0 du graphe par les valeurs
while ($ligne = $qU->fetch()) {
    $dateDuJour = $ligne['j'];
    $nombreInscrits = $ligne['tot'];

    $stats[$dateDuJour]['users'] = (int)$nombreInscrits;
}

// variables des listes
$labels = [];
$dataAnnonces = [];
$dataUsers = [];

// applique les valeurs au graphe
foreach ($stats as $date => $v) {
    $labels[] = date("d/m", strtotime($date));
    $dataAnnonces[] = $v['annonces'];
    $dataUsers[] = $v['users'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="style_web.css" />
    <title>Evolution des statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
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
    <div class="zone-contenu-admin" style="display: flex; gap: 40px; width: 80%; max-width: 1400px; margin-left: auto; margin-right: auto; margin-bottom: 380px;">
        <div style="flex: 1; min-width: 0;">
            <h2 style="text-align: center;">Evolution du nombre d'annonces</h2>
            <canvas id="chartAnnonces"></canvas>
        </div>
        
        <div style="flex: 1; min-width: 0;">
            <h2 style="text-align: center;">Evolution du nombre d'utilisateurs</h2>
            <canvas id="chartUsers"></canvas>
        </div>
    </div>

    <script>
        const ctxAnnonces = document.getElementById('chartAnnonces').getContext('2d');
        new Chart(ctxAnnonces, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Nouvelles annonces',
                    data: <?php echo json_encode($dataAnnonces); ?>,
                    fill: true,
                    backgroundColor: 'rgba(85, 87, 218, 0.2)',
                    borderColor: 'rgb(98, 61, 182)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        suggestedMax: 10
                    }
                }
            }
        });

        const ctxUsers = document.getElementById('chartUsers').getContext('2d');
        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: <?php echo json_encode($dataUsers); ?>,
                    fill: true,
                    backgroundColor: 'rgba(86, 54, 228, 0.2)',
                    borderColor: 'rgb(67, 35, 153)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        suggestedMax: 5
                    }
                }
            }
        });
    </script>
</body>

</html>