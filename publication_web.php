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
    <main>
        <div class="publi-container">
            <h2>PUBLIER UNE NOUVELLE ANNONCE</h2>
            <form method="POST" class="publish">
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
                                <input type="text" placeholder="ex: 450">
                                <span>€</span>
                            </div>
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
                <div class="block category-group">
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
                <div class="block photos-group">
                    <label>PHOTOS</label>
                    <div class="photo-upload-container">
                        <input type="file" id="file" name="file" accept="image/*" multiple style="display: none;" />
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
                    <a href="#" class="cancel-link">Annuler</a>
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