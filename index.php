<?php require_once 'datab_web.php'; ?>

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
    <div class="hero">
        <h1>TechMarket</h1>
        <p>La plateforme de petites annonces pour votre matériel high-tech d'occasion</p>
    </div>

    <div class="bloc-recherche">
        <form method="GET" action="/recherche.php">

            <div class="recherche-principale">
                <input type="search" name="q" placeholder="Rechercher un produit...">
                <button type="submit">Rechercher</button>
            </div>

            <div class="filtres-ligne">
                <select name="categorie">
                    <option value="">Toutes les catégories</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Téléphone">Téléphone</option>
                    <option value="Audio/Vidéo">Audio/Vidéo</option>
                </select>

                <select name="sous_categorie">
                    <option value="">Toutes les sous-catégories</option>
                    <option value="Portables">Portables</option>
                    <option value="Fixes">Fixes</option>
                    <option value="Smartphones">Smartphones</option>
                    <option value="Casques">Casques</option>
                </select>

                <input type="number" name="prix_min" placeholder="Prix min">
                <input type="number" name="prix_max" placeholder="Prix max">
                <input type="text" name="ville" placeholder="Ville">

                <select name="etat">
                    <option value="">Tous les états</option>
                    <option value="neuf">Neuf</option>
                    <option value="tres_bon_etat">Très bon état</option>
                    <option value="bon_etat">Bon état</option>
                    <option value="etat_correct">État correct</option>
                </select>
            </div>

            <div class="filtres-ligne">
                <select name="tri">
                    <option value="date_desc">Plus récentes</option>
                    <option value="date_asc">Plus anciennes</option>
                    <option value="prix_asc">Prix croissant</option>
                    <option value="prix_desc">Prix décroissant</option>
                </select>
                <button type="submit">Filtrer</button>
                <a href="/index.php" class="btn-reinit">Réinitialiser</a>
            </div>

        </form>
    </div>

    <h2 class="titre-section">Annonces récentes</h2>
    <?php require_once 'includes/annonces.php'; ?>

</body>

</html>