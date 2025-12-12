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
