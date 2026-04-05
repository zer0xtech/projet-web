<?php

function analyserAnnonceAvecOllama($titre, $description, $prix, $categorie, $sous_categorie, $cats_principales, $sous_cats)
{
    $url = 'http://localhost:11434/api/generate';

    $liste_cat = implode(", ", $cats_principales);
    $liste_sous_cat = implode(", ", $sous_cats);
    $prompt = "Tu es un modérateur expert pour TechMarket, un site exclusif de revente de matériel high-tech d'occasion.
    Analyse scrupuleusement l'annonce suivante :
    - Titre : $titre
    - Description : $description
    - Prix : $prix €
    - Catégorie actuelle : $categorie
    - Sous-catégorie actuelle : $sous_categorie
    
    CATÉGORIES PRINCIPALES AUTORISÉES : $liste_cat
    SOUS-CATÉGORIES AUTORISÉES : $liste_sous_cat
    
    Tu dois obligatoirement vérifier ces 5 points :
    1. Contenu inapproprié : signale tout langage vulgaire, contenu illégal, incitation à la violence ou présence de coordonnées.
    2. Orthographe et grammaire : identifie les fautes majeures.
    3. Catégorisation : Le TITRE est ton seul indicateur fiable. Ne change la catégorie que si elle est manifestement fausse par rapport au titre.
    4. Prix : alerte si le prix est anormalement haut ou bas.
    5. HORS-SUJET STRICT : Si l'objet n'est manifestement PAS un produit High-Tech (ex: marteau, bricolage, vêtements, etc.) :
       - Tu NE DOIS l'associer à AUCUNE catégorie technologique.
       - Tu dois mettre 'Hors-sujet' dans les champs catégorie et sous-catégorie suggérées.
       - Tu dois exiger le refus de l'annonce.

    Tu DOIS renvoyer ta réponse UNIQUEMENT sous la forme d'un objet JSON valide.
    Utilise exactement cette structure :
    {
        \"approprie\": true ou false,
        \"niveau_confiance\": \"élevé\", \"moyen\" ou \"faible\",
        \"problemes_detectes\": [\"problème 1\"...],
        \"suggestions_correction\": [\"Si l'objet n'est pas High-Tech, écris UNIQUEMENT : 'Cet objet n'est pas destiné à ce site web (produit non high-tech)'. Sinon, donne tes suggestions classiques.\"],
        \"categorie_suggeree\": \"Nom exact de la catégorie ou 'Hors-sujet'\",
        \"sous_categorie_suggeree\": \"Nom exact de la sous-catégorie ou 'Hors-sujet'\",
        \"decision_recommandee\": \"validation\", \"refus\" ou \"validation_avec_modifications\"
    }";

    $data = [
        'model' => 'qwen2.5:3b',
        'prompt' => $prompt,
        'format' => 'json',
        'stream' => false
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

    return ['success' => true, 'data' => $donnees_ia];
}
