<?php
require_once 'data/user_test.php';
$bdd = db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // La catégorie est maintenant envoyée par le formulaire en POST
    publication($_POST['categorie1'] ?? '');
}

$reqCat = $bdd->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY nom ASC");
$categories_principales = $reqCat->fetchAll();

$reqSousCat = $bdd->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY nom ASC");
$sous_categories = $reqSousCat->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Publication</title>
    <link rel="stylesheet" href="style_web.css" />
</head>

<body>
    <?php require_once 'includes/navbar.php'; ?>
    <main>

        <div class="publi-container">
            <h2>PUBLIER UNE NOUVELLE ANNONCE</h2>
            <form method="POST" class="publish" enctype="multipart/form-data">
                <div class="block">
                    <label for="title">TITRE</label>
                    <input type="text" id="title" name="title" placeholder="ex: iPhone 13 Pro 128Go Noir" required>
                </div>
                <div class="block description-prix-state">
                    <div class="block-inter">
                        <label for="description">DESCRIPTION</label>
                        <textarea id="description" name="description" placeholder="Décrivez l'état de votre article, ses fonctionnalités, ses défauts éventuels..." required></textarea>
                    </div>
                    <div class="block-inter prix-state">
                        <div class="prix-group">
                            <label for="price">PRIX</label>
                            <div class="inter-prix-group">
                                <input type="text" id="price" name="price" placeholder="ex: 450" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                <span>€</span>
                            </div>
                        </div>
                        <div class="state-group">
                            <label for="state">ÉTAT</label>
                            <select id="state" name="state" required>
                                <option value="" disabled selected hidden>Choisir l'état...</option>
                                <option value="Neuf">Neuf</option>
                                <option value="Très bon">Très bon</option>
                                <option value="Bon">Bon</option>
                                <option value="correct">Correct</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="block category-group">
                    <div class="category-selects">
                        <div class="category-column">
                            <label for="categorie1">CATÉGORIE (Niveau 1)</label>
                            <select name="categorie1" id="categorie1" required>
                                <option value="" selected disabled>Sélectionner une catégorie...</option>
                                <?php foreach ($categories_principales as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="category-column">
                            <label for="category2">CATÉGORIE (Niveau 2)</label>
                            <select id="category2" name="category2" required>
                                <option value="" selected disabled>Sélectionner une sous-catégorie...</option>
                                <?php foreach ($sous_categories as $sub): ?>
                                    <option value="<?php echo $sub['id']; ?>" data-parent="<?php echo $sub['parent_id']; ?>">
                                        <?php echo htmlspecialchars($sub['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
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
                    </div>
                </div>
                <div class="cancel">
                    <button type="submit" class="submit-button">PUBLIER<br>(statut : en attente)</button>
                    <a href="publication_web.php" class="cancel-link">Annuler</a>
                </div>

            </form>
        </div>
    </main>
    <script>
        const selectCategorie = document.getElementById('categorie1');
        const selectSousCategorie = document.getElementById('category2');
        const toutesLesSousCategories = Array.from(selectSousCategorie.options).filter(opt => opt.value !== "");

        function filtrerSousCategories() {
            const idCategorieChoisie = selectCategorie.value;
            selectSousCategorie.innerHTML = '<option value="" disabled selected>Sélectionner une sous-catégorie...</option>';

            toutesLesSousCategories.forEach(function(option) {
                if (option.getAttribute('data-parent') === idCategorieChoisie) {
                    selectSousCategorie.appendChild(option);
                }
            });
        }

        selectCategorie.addEventListener('change', filtrerSousCategories);

        if (selectCategorie.value !== "") {
            filtrerSousCategories();
        }

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