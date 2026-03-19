<?php
require_once 'data/user_test.php';

$categorieChoisie = $_GET['categorie1'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    publication($categorieChoisie);
}
?>

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
    <main>
        <?php if ($categorieChoisie === ''): ?>
            <div class="overlay-flou">
                <h2>Dans quelle catégorie souhaitez-vous publier ?</h2>
                <form method="GET" action="">
                    <button type="submit" class="submit-button" name="categorie1" value="informatique">informatique</button>
                    <button type="submit" class="submit-button" name="categorie1" value="telephone">telephone</button>
                    <button type="submit" class="submit-button" name="categorie1" value="audio/video">audio/video</button>
                </form>
            </div>
        <?php endif; ?>
        <div class="publi-container">
            <h2>PUBLIER UNE NOUVELLE ANNONCE</h2>
            <form method="POST" class="publish" enctype="multipart/form-data">
                <div class="block">
                    <label for="title">TITRE</label>
                    <input type="text" id="title" name="title" placeholder="ex: iPhone 13 Pro 128Go Noir">
                </div>
                <div class="block description-prix-state">
                    <div class="block-inter">
                        <label for="description">DESCRIPTION</label>
                        <textarea id="description" name="description" placeholder="Décrivez l'état de votre article, ses fonctionnalités, ses défauts éventuels..."></textarea>
                    </div>
                    <div class="block-inter prix-state">
                        <div class="prix-group">
                            <label for="price">PRIX</label>
                            <div class="inter-prix-group">
                                <input type="text" id="price" name="price" placeholder="ex: 450" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <span>€</span>
                            </div>
                        </div>
                        <div class="state-group">
                            <label for="state">ÉTAT</label>
                            <select id="state" name="state">
                                <option disabled selected hidden>Choisir l'état...</option>
                                <option value="Neuf">Neuf</option>
                                <option value="Très bon">Très bon</option>
                                <option value="Bon">Bon</option>
                                <option value="correct">correct</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="block category-group">
                    <div class="category-selects">
                        <div class="category-column">
                            <label for="category1">CATÉGORIE (Niveau 1)</label>
                            <select name="categorie1" id="categorie1">
                                <?php if ($categorieChoisie === ''): ?>
                                    <option value="" selected disabled>Sélectionner une catégorie...</option>
                                <?php else: ?>
                                    <?php if ($categorieChoisie === 'informatique'): ?>
                                        <option value="informatique" selected disabled>Informatique</option>
                                    <?php elseif ($categorieChoisie === 'telephone'): ?>
                                        <option value="telephone" selected disabled>Téléphone</option>
                                    <?php elseif ($categorieChoisie === 'audio/video'): ?>
                                        <option value="audio_video" selected disabled>Audio/Vidéo</option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="category-column">
                            <label for="category2">CATÉGORIE (Niveau 2)</label>
                            <select id="category2" name="category2">
                                <?php if ($categorieChoisie === 'informatique'): ?>
                                    <<optgroup label="Ordinateurs">
                                        <option value="portables">Portables</option>
                                        <option value="fixes">Fixes</option>
                                        <option value="gaming">Gaming</option>
                                        </optgroup>

                                        <optgroup label="Composants">
                                            <option value="cartes_graphiques">Cartes graphiques</option>
                                            <option value="processeurs">Processeurs</option>
                                            <option value="carte_mere">Carte mère</option>
                                        </optgroup>

                                        <optgroup label="Périphériques">
                                            <option value="souris">Souris</option>
                                            <option value="claviers">Claviers</option>
                                            <option value="ecrans">Écrans</option>
                                        </optgroup>
                                    <?php elseif ($categorieChoisie === 'telephone'): ?>
                                        <optgroup label="Marques">
                                            <option value="iphone">iPhone</option>
                                            <option value="samsung">Samsung</option>
                                            <option value="xiaomi">Xiaomi</option>
                                        </optgroup>

                                        <optgroup label="Accessoires">
                                            <option value="coques">Coques</option>
                                            <option value="chargeurs">Chargeurs</option>
                                        </optgroup>
                                    <?php elseif ($categorieChoisie === 'audio/video'): ?>
                                        <optgroup label="Casques">
                                            <option value="gaming">Gaming</option>
                                            <option value="musique">Musique</option>
                                            <option value="confort">Confort</option>
                                        </optgroup>

                                        <optgroup label="Enceintes">
                                            <option value="hi_fi">Hi-Fi</option>
                                            <option value="home_cinema">Home Cinéma</option>
                                            <option value="professionnel">Professionnel</option>
                                        </optgroup>

                                        <optgroup label="Caméras">
                                            <option value="compacts">Compacts</option>
                                            <option value="hybrides">Hybrides</option>
                                            <option value="reflex">Reflex</option>
                                        </optgroup>
                                    <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="block photos-group">
                    <label for="file">PHOTOS</label>
                    <div class="photo-upload-container">
                        <input type="file" id="file" name="file[]" accept="image/*" multiple style="display: none;" />
                        <div class="photo-slot">
                            <span style="font-size: 20px; margin-bottom: 5px;">+</span>
                            Télécharger vos <br> photos
                        </div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                        <div class="photo-slot"></div>
                    </div>
                </div>
                <div class="cancel">
                    <button type="submit" class="submit-button">PUBLIER<br>(statut : en attente)</button>
                    <a href="publication_web.php" class="cancel-link">Annuler</a>
                </div>

            </form>
        </div>
        <div class="existing-ads-container">
            <h3>GÉRER VOS ANNONCES EXISTANTES</h3>
        </div>
    </main>
    <script>
        const fileInput = document.getElementById('file');
        const photoSlots = document.querySelectorAll('.photo-slot');

        photoSlots.forEach(slot => {
            slot.addEventListener('click', () => {
                fileInput.click();
            });
        });

        fileInput.addEventListener('change', function() {
            const files = Array.from(this.files);

            photoSlots.forEach(slot => {
                slot.innerHTML = '';
            });

            files.forEach((file, index) => {
                if (index < photoSlots.length) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    photoSlots[index].appendChild(img);
                }
            });
        });
    </script>
</body>

</html>