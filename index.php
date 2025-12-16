<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button>Créer un post</button>
    <?php
        function db(): ?PDO {
            try {
                return new PDO(
                    'mysql:host=127.0.0.1;dbname=bibliotheque;charset=utf8',
                    'root',
                    ''
                );
            } catch (Exception $e) {
                echo "Erreur de connexion à la BDD";
                return null;
            }
            
        }
        $addUsers = db()->prepare("INSERT INTO users (username, password) VALUES (?,?)");
        $addUsers->execute([$pseudo, md5($password)]);
        // $printUsers = ("SELECT * FROM users");
        echo $printUsers;
    ?>
</body>
</html>
<?php 

// listeAttentes($posts);

class Moderation {
    public $post_id;
    public $user_id;
    public $post_content;
    public $post_description;
    public $post_image;

    function moderatePost() {
        $moderatePostOption = readline('Vous etes en train de voir le post numéro' . ($post_id) . ', voulez vous modérer ce poste ?');
    }
}
?>