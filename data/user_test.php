<?php
require_once 'datab_web.php';

function estConnecte(): bool
{
    return (isset($_SESSION['username']) && !empty($_SESSION['username']));
}

function testMotdePasse($username, $password)
{
    $test = db()->prepare("SELECT COUNT(*) FROM utilisateurs WHERE pseudo = ? AND mot_de_passe = ?");
    $test->execute([$username, md5($password)]);
    return $test->fetchColumn(0) > 0;
}

function login($username, $password)
{
    if (!testMotdePasse($username, $password)) {
        return false;
    }
    $req = db()->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
    $req->execute([$username]);
    $id = $req->fetchColumn(0);

    // Stockage de l'ID et du pseudo
    $_SESSION['userid'] = $id;
    $_SESSION['username'] = $_POST['username'];

    return true;
}

function inscription()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['ConfirmPassword'])) {
        if ($_POST['password'] == $_POST['ConfirmPassword']) {
            $verif = db()->prepare("SELECT COUNT(*) FROM utilisateurs WHERE pseudo = ?");
            $verif->execute([$_POST['username']]);
            if ($verif->fetchColumn() > 0) {
                return "Pseudo déjà utilisé";
            }
            $ajout = db()->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe, date_inscription) VALUES (?, ?, NOW())");
            $ajout->execute([$_POST['username'], md5($_POST['password'])]);
            return true;
        } else {
            return "Mots de passe ne correspondent pas";
        }
    }
    return false;
}
