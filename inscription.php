<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    // die;

    function db(): ?PDO
    {
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
    $addUsers->execute([$_POST['$pseudo'], md5($_POST['$password'])]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        Inscription
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password">
        <button type="submit">Inscription</button>
    </form>
</body>
</html>