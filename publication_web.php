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
    <?php require_once 'includes/catalog.php'; ?>
    <main>
        <div class="publish-form-container">
            <h2>PUBLIER UNE NOUVELLE ANNONCE</h2>

            <form>
                <div class="field-group">
                    <label for="title">TITRE</label>
                    <input type="text" id="title" name="title" placeholder="ex: iPhone 13 Pro 128Go Noir">
                </div>

                <div class="field-group description-prix-state">
                    <div class="field-group-inner">
                        <label for="description">DESCRIPTION</label>
                        <textarea id="description" name="description" placeholder="Décrivez l'état de votre article, ses fonctionnalités, ses défauts éventuels..."></textarea>
                    </div>
                    <div class="field-group-inner prix-state">
                        <div class="prix-group">
                            <label for="price">PRIX</label>
                            <input type="number" id="price" name="price" placeholder="ex: 450"><span>€</span>
                        </div>
                        <div class="state-group">
                            <label for="state">ÉTAT</label>
                            <select id="state" name="state">
                                <option value="">Choisir l'état...</option>
                                <option value="new">Neuf</option>
                                <option value="very_good">Très bon état</option>
                                <option value="good">Bon état</option>
                                <option value="correct">État correct</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field-group category-group">
                    <div class="category-selects">
                        <div class="category-column">
                            <label for="category1">CATÉGORIE (Niveau 1)</label>
                            <select id="category1" name="category1">
                                <option value="">Sélectionner une catégorie...</option>
                                <option value="telephone">Téléphone</option>
                                <option value="informatique">Informatique</option>
                                <option value="audio_video">Audio/Vidéo</option>
                            </select>
                        </div>
                        <div class="category-column">
                            <label for="category2">CATÉGORIE (Niveau 2)</label>
                            <select id="category2" name="category2">
                                <option value="">Sélectionner une sous-catégorie...</option>
                                <option value="smartphone">Smartphone</option>
                                <option value="laptop">Ordinateur portable</option>
                                <option value="headphones">Casque</option>
                                <option value="camera">Caméra</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field-group photos-group">
                    <label>PHOTOS</label>
                    <div class="photo-upload-container">
                        <div class="photo-slot upload-slot">
                            <span>📷</span>
                            <span>Télécharger vos photos</span>
                        </div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                    </div>
                </div>

                <div class="submit-cancel-btns">
                    <button type="submit" class="submit-btn">SOUMETTRE POUR MODÉRATION (statut : en_attente)</button>
                    <a href="#" class="cancel-link">Annuler</a>
                </div>

            </form>
        </div>

        <div class="existing-ads-container">
            <h3>GÉRER VOS ANNONCES EXISTANTES</h3>
            <ul class="ads-list">
                <li class="ad-item">
                    <div class="ad-image-details">
                        <img src="https://via.placeholder.com/100x80" alt="iPhone 13 Pro">
                        <div class="ad-details">
                            <span class="ad-title">iPhone 13 Pro 128Go Noir</span>
                            <span class="ad-price">450 €</span>
                        </div>
                    </div>
                    <div class="ad-actions">
                        <button class="modify-btn">MODIFIER</button>
                        <button class="delete-btn">SUPPRIMER</button>
                    </div>
                </li>
                <li class="ad-item">
                    <div class="ad-image-details">
                        <img src="https://via.placeholder.com/100x80" alt="Ordinateur Portable">
                        <div class="ad-details">
                            <span class="ad-title">Ordinateur Portable / Audio/Vidéo</span>
                            <span class="ad-price">--- €</span>
                        </div>
                    </div>
                    <div class="ad-actions">
                        <button class="modify-btn">MODIFIER</button>
                        <button class="delete-btn">SUPPRIMER</button>
                    </div>
                </li>
            </ul>
        </div>
    </main>
</body>

</html>