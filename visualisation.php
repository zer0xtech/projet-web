<?php
    require_once("./includes/navbar.php")
?>
<!DOCTYPE html>
<html lang="fr">

<style>
.blocs {
    display: flex;
    align-items: center;
    margin-top : 100px;
}

.annonces {
    width: 330px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 5px 6px;
    padding: 5px;
    margin-top: 50px;
    text-align: center;
}

.titre {
    text-align: center;
}

.imagesannonces {
    align-items: center;
    max-height: 50vh;
}
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>login</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<html>
<div class="blocs">

    <div class="annonces">
        <div class="titre">
            <h2>Macbook Pro</h2>
            <img src="/images/rabbit time.png" alt="" class="imagesannonces">
        </div>
        
        <div class="bio">
            <p>Description : Ordinateur Apple Dernière génération</p>
            <p>Prix : 300$</p>
            <p>État : Neuf</p>
            <p>Catégorie : Informatique</p>
            <p>Statut : En Attente</p>
        </div>
    </div>
</div>
</html>