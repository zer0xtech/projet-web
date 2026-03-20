<?php 
$posts = [1, 2, 3];

function listeAttentes(array $posts) {
    $waiting_posts = [];

    foreach ($posts as $post) {

        // print_r("Vous etes en train de voir le post " . $post . "." . PHP_EOL);
    }
}

function moderationPosts(array $posts) {
    $accepted_posts = [];
}

// listeAttentes($posts);

class Moderation {
    public $post_id;
    public $user_id;
    public $post_content;
    public $post_description;
    public $post_image;

    function moderatePost() {
        $moderatePostOption = readline('Vous etes en train de voir le post numéro' . ($post_id) . ', voulez vous modérer ce poste ?');

        if ($moderatePostOption == 'oui') {
            
    }
}



// $post_1 = new Moderation();
// $post_1->post_content = "lorem ipsum";
// // echo $post_1->post_content;

// function modChoice() {
//     $modActionChoice = readline('Quel modification voulez-vous faire ? [titre / contenu / description] : ');

//     while ($modActionChoice != 'titre' && $modActionChoice != 'description' && $modActionChoice != 'contenu') {
//         print("Erreur, réessayez." . PHP_EOL);
//         $mod_choice = readline('Quel modification voulez-vous faire ? [titre / contenu / description] : ');
//     }

//     if ($modActionChoice == 'titre') {
//         print("OK");
//     }
//     elseif ($modActionChoice == 'description') {
//         print("OK");
//     }
//     elseif ($modActionChoice == 'contenu')
//         print("OK");
// }

// add_content();