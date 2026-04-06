<?php

function analyserAnnonceAvecOllama($titre, $description, $prix, $categorie, $sous_categorie, $cats_principales, $sous_cats)
{
    $url = 'http://localhost:11434/api/generate';

    $liste_cat = implode(", ", $cats_principales);
    $liste_sous_cat = implode(", ", $sous_cats);
    $prompt = "Tu es l'IA experte en modération de TechMarket.
    Analyse l'annonce avec une logique implacable. NE SOIS PAS BAVARD.

    --- DONNÉES DE L'ANNONCE ---
    Titre : $titre
    Description : $description
    Prix : $prix €
    Catégorie actuelle : $categorie
    Sous-catégorie actuelle : $sous_categorie

    --- LISTES AUTORISÉES ---
    Catégories : $liste_cat
    Sous-catégories : $liste_sous_cat

    --- RÈGLES STRICTES ANTI-HALLUCINATION ---
    1. DESCRIPTION VIDE : Si la description est vide ou presque vide, n'invente AUCUNE description à la place du vendeur. Écris EXACTEMENT 'Veuillez ajouter une description' dans les suggestions, et ne signale aucune faute d'orthographe (puisqu'il n'y a pas de texte).
    2. PRIX : Si le prix te semble totalement incohérent (trop haut ou trop bas), indique UNIQUEMENT 'Prix anormal' dans les problèmes détectés. Ne justifie JAMAIS pourquoi et ne dis pas si c'est une arnaque.
    3. TITRE : Ne suggère JAMAIS de modifier le titre pour remettre exactement le même titre.
    4. CATÉGORIE : Si la catégorie d'origine est fausse, corrige-la (ex: un iPhone va dans Téléphonie > smartphone). 

    --- FORMAT DE RÉPONSE JSON OBLIGATOIRE ---
    Renvoie uniquement ce JSON :
    {
        \"approprie\": \"oui\" ou \"non\",
        \"niveau_confiance\": \"élevé\", \"moyen\" ou \"faible\",
        \"problemes_detectes\": \"Indique les problèmes factuels (ex: 'Catégorie fausse', 'Prix anormal', 'Description manquante'). Si l'annonce est normale, écris 'Aucun'.\",
        \"suggestions_correction\": [
            \"Mets uniquement des ordres courts. Ex: 'Changez la catégorie', 'Ajoutez une description'. Ne justifie pas tes choix.\"
        ],
        \"categorie_suggeree\": \"Nom exact de la catégorie\",
        \"sous_categorie_suggeree\": \"Nom exact de la sous-catégorie\",
        \"decision_recommandee\": \"validation\", \"refus\" ou \"validation_avec_modifications\"
    }";

    $data = [
        'model' => 'qwen2.5:3b',
        'prompt' => $prompt,
        'format' => 'json',
        'stream' => false,
        'options' => [
            'temperature' => 0.0
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $erreur = curl_error($ch);
        return ['success' => false, 'message' => "Erreur de connexion IA : " . $erreur];
    }

    $resultat = json_decode($response, true);

    if (isset($resultat['error'])) {
        return ['success' => false, 'message' => "Erreur du modèle Ollama : " . $resultat['error']];
    }

    if (!isset($resultat['response'])) {
        return ['success' => false, 'message' => "Format de réponse inattendu de la part d'Ollama."];
    }

    $donnees_ia = json_decode($resultat['response'], true);
    if ($donnees_ia === null) {
        return ['success' => false, 'message' => "L'IA n'a pas respecté le format JSON demandé."];
    }

    // Ton excellente idée pour sécuriser les retours de l'IA
    $structure_par_defaut = [
        'approprie' => 'non défini',
        'niveau_confiance' => 'faible',
        'problemes_detectes' => 'Aucun détail fourni',
        'suggestions_correction' => [],
        'categorie_suggeree' => 'Inconnue',
        'sous_categorie_suggeree' => 'Inconnue',
        'decision_recommandee' => 'refus'
    ];

    $donnees_finales = array_merge($structure_par_defaut, $donnees_ia);

    return ['success' => true, 'data' => $donnees_finales];
}
