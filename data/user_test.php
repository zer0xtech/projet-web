<?php
require_once 'datab_web.php';

function estConnecte(): bool
{
    return (isset($_SESSION['email']) && !empty($_SESSION['email']));
}

function testMotdePasse($email, $password)
{
    $test = db()->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND password = ?");
    $test->execute([$email, md5($password)]);
    return $test->fetchColumn(0) > 0;
}

function login($email, $password)
{
    if (!testMotdePasse($email, $password)) {
        return false;
    }
    $req = db()->prepare("SELECT id FROM users WHERE email = ?");
    $req->execute([$email]);
    $id = $req->fetchColumn(0);

    $_SESSION['userid'] = $id;
    $_SESSION['email'] = $_POST['email'];

    return true;
}

function inscription()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']) && isset($_POST['ville']) && isset($_POST['password']) && isset($_POST['ConfirmPassword'])) {
        if ($_POST['password'] == $_POST['ConfirmPassword']) {
            $verif = db()->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $verif->execute([$_POST['email']]);
            if ($verif->fetchColumn() > 0) {
                return "Email déjà utilisé";
            }
            $ajout = db()->prepare("INSERT INTO users (email, prenom, nom, phone, ville, password, date_inscription) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $ajout->execute([$_POST['email'], $_POST['prenom'], $_POST['nom'], $_POST['telephone'], $_POST['ville'], md5($_POST['password'])]);
            return true;
        } else {
            return "Mots de passe ne correspondent pas";
        }
    }
    return false;
}
