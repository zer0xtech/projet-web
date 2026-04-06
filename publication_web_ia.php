<?php
require_once 'data/user_test.php';
require_once 'datab_web.php';

$pdo = db();
$categorieChoisie = $_GET['categorie1'] ?? '';
$modeChoisie = $_GET['mode'] ?? '';

$reqCat = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY nom ASC");
$categories_principales = $reqCat->fetchAll(PDO::FETCH_ASSOC);

$toutes_sous_categories = $pdo->query("SELECT * FROM categories WHERE parent_id IS NOT NULL ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    publication_ia($categorieChoisie);
}
?>

<!DOCTYPE html>
<html lang="en">

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
        <?php if ($modeChoisie === ''): ?>
            <div class="overlay-flou">
                <h2>Avec quel mode souhaitez-vous publier ?</h2>
                <form method="GET" action="">
                    <button type="submit" class="submit-button" name="mode" value="classique">Mode Classique</button>
                    <button type="submit" class="submit-button" name="mode" value="assiste">Mode Assisté</button>
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
                        <?php if ($modeChoisie === 'classique'): ?>
                            <label for="question">DESCRIPTION</label>
                            <textarea name="question" id="question" rows="4" style="flex: 1; width: 500px;" placeholder="Décrivez l'état de votre article, ses fonctionnalités, ses défauts éventuels..."></textarea>
                            <button type="button" id="submit" class="submit_ia">Envoyer à l'IA</button>
                            <span id="chargement" style="display: none;">Chargement en cours...</span>
                            <span id="erreur" style="display: none;"></span>
                            <textarea name="reponse" id="reponse" style="flex: 1;"></textarea>
                        <?php else: ?>
                            <div class="form_ia">
                                <label for="question">DESCRIPTION (remplissez le formulaire)</label>
                                <div>
                                    <label for="categorie_ia_desc">Catégorie</label>
                                    <input type="text" id="categorie_ia_desc" name="categorie_ia_desc">
                                    <label for="marque">Marque</label>
                                    <input type="text" id="marque" name="marque">
                                    <label for="modele">Modèle</label>
                                    <input type="text" id="modele" name="modele">
                                    <label for="etat">État</label>
                                    <input type="text" id="etat" name="etat">
                                    <label for="detail_etat">Détails de l'État</label>
                                    <input type="text" id="detail_etat" name="detail_etat">
                                    <label for="annee">Année</label>
                                    <input type="text" id="annee" name="annee">
                                    <label for="carac_princ">Caractéristiques principales </label>
                                    <input type="text" id="carac_princ" name="carac_princ">
                                    <div style="display: flex; flex-direction: row; gap: 5px">
                                        <label for="access_include">Accessoires inclus</label><label for="" style="color: red;">(optionnel)</label>
                                    </div>
                                    <input type="text" id="access_include" name="access_include">
                                    <div style="display: flex; flex-direction: row; gap: 5px">
                                        <label for="reason_sell">Raison de la vente </label><label for="" style="color: red;">(optionnel)</label>
                                    </div>
                                    <input type="text" id="reason_sell" name="reason_sell">
                                </div>
                                <button type="button" id="submit" style="width: 200px; margin: 10px 0px 10px 0px;">Générer la description</button>
                                <span id="chargement" style="display: none;">Chargement en cours...</span>
                                <span id="erreur" style="display: none;"></span>
                            </div>
                        <?php endif; ?>
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
                        <?php if ($modeChoisie === 'classique'): ?>
                            <p style="color: #000000;"><-- description soumise au formulaire</p>
                                <?php else: ?>
                                    <textarea class="desc_rep_ia" name="reponse" id="reponse" style="height: 600px; margin-top: 20px"></textarea>
                                    <p style="color: #000000;">formulaire généreé <span style="font-size: 30px;">&#11023;</span></p>
                                <?php endif; ?>
                    </div>
                </div>
                <div class="category-selects">
                    <div class="category-column">
                        <label for="categorie1">CATÉGORIE (Niveau 1)</label>
                        <select name="categorie1" id="categorie1">
                            <option value="" selected disabled>Sélectionnez une catégorie...</option>
                            <?php foreach ($categories_principales as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['nom'] === $categorieChoisie ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="category-column">
                        <label for="category2">CATÉGORIE (Niveau 2)</label>
                        <select id="category2" name="category2">
                            <option value="" disabled selected>Sélectionnez une sous-catégorie</option>
                        </select>
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
</body>
<script>
    const toutesLesSousCategories = <?= json_encode($toutes_sous_categories) ?>;

    const selectCat1 = document.getElementById('categorie1');
    const selectCat2 = document.getElementById('category2');

    selectCat1.addEventListener('change', function() {
        const parentId = this.value;

        selectCat2.innerHTML = '<option value="" disabled selected>Sélectionnez une sous-catégorie</option>';

        const sousCats = toutesLesSousCategories.filter(c => c.parent_id == parentId);

        sousCats.forEach(sousCat => {
            const option = document.createElement('option');
            option.value = sousCat['id'];
            option.textContent = sousCat['nom'];
            selectCat2.appendChild(option);
        });
    });
</script>
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

<script type="text/javascript">
    var submit = document.getElementById('submit');
    var reponse, chargement, erreur, question;

    function genererPromptClassique() {
        question = document.getElementById('question');
        return `Tu es un expert en rédaction d'annonces de vente d'occasion de matériel High-Tech.
        Ton rôle est de réécrire la description suivante pour la rendre professionnelle, vendeuse, claire et sans aucune faute d'orthographe.

        --- TEXTE À RÉÉCRIRE ---    
        ${question.value}
        ------------------------

        RÈGLES OBLIGATOIRES :
    1. N'ajoute aucune introduction du type "Voici votre annonce" ou "Je vous propose".
    2. N'ajoute aucune conclusion du type "Bonne vente".
    3. Va directement à l'essentiel et structure le texte de manière aérée (utilise des puces si besoin).
    4. Ne mens pas et ne rajoute pas d'informations techniques qui ne sont pas dans le texte d'origine.
    5. Renvoie UNIQUEMENT la description prête à être copiée-collée.`;
    }

    function genererPromptAssiste() {
        const categorie = document.getElementById('categorie_ia_desc')?.value ?? '';
        const marque = document.getElementById('marque')?.value ?? '';
        const modele = document.getElementById('modele')?.value ?? '';
        const etat = document.getElementById('etat')?.value ?? '';
        const detail_etat = document.getElementById('detail_etat')?.value ?? '';
        const annee = document.getElementById('annee')?.value ?? '';
        const carac = document.getElementById('carac_princ')?.value ?? '';
        const access = document.getElementById('access_include')?.value ?? '';
        const raison = document.getElementById('reason_sell')?.value ?? '';

        let prompt = `Rédige une description d'annonce de vente d'occasion de manière naturelle, honnête et vendeuse.

RÈGLES STRICTES :
1. Écris à la première personne du singulier ("Je vends mon...").
2. Fais un texte fluide sous forme de 2 ou 3 petits paragraphes.
3. N'utilise AUCUNE liste à puces (pas de tirets ou de bullet points).
4. Ne mets AUCUNE introduction ("Voici l'annonce") ni AUCUNE conclusion ("Bonne journée"). Renvoie uniquement le texte final.

Voici les informations à intégrer de manière rédigée :
- Catégorie : ${categorie}
- Marque : ${marque}
- Modèle : ${modele}
- État général : ${etat}
- Détails sur l'état : ${detail_etat}
- Année d'achat/sortie : ${annee}
- Caractéristiques : ${carac}`;

        if (access) prompt += `\n- Accessoires inclus : ${access}`;
        if (raison) prompt += `\n- Cause de la vente : ${raison}`;

        return prompt;
    }

    function promptOllama(contenu) {
        var prompt = {
            "model": "qwen2.5:3b",
            "messages": [{
                "role": "user",
                "content": contenu
            }],
            "stream": false
        };

        chargement.style.display = 'inline';
        erreur.style.display = 'none';

        fetch('http://localhost:11434/api/chat', {
                method: "POST",
                body: JSON.stringify(prompt)
            })
            .then(response => response.json())
            .then(response => {
                if (response.error) throw new Error(response.error);
                reponse.value = response.message.content;
            })
            .catch(err => {
                erreur.style.display = 'inline';
                erreur.innerText = 'Erreur : ' + err.message;
            })
            .finally(() => {
                chargement.style.display = 'none';
            });
    }

    submit.addEventListener('click', function(event) {
        event.preventDefault();

        reponse = document.getElementById('reponse');
        chargement = document.getElementById('chargement');
        erreur = document.getElementById('erreur');

        const estAssiste = document.getElementById('marque') !== null;
        if (estAssiste) {
            promptOllama(genererPromptAssiste());
        } else {
            promptOllama(genererPromptClassique());
        }
    });
</script>


</html>