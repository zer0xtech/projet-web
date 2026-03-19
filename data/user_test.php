<?php
require_once 'datab_web.php';

function estConnecte(): bool
{
    return (isset($_SESSION['email']) && !empty($_SESSION['email']));
}

function testMotdePasse($email, $password)
{
    $test = db()->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND mot_de_passe = ?");
    $test->execute([$email, md5($password)]);
    return $test->fetchColumn(0) > 0;
}

function login($email, $password)
{
    if (!testMotdePasse($email, $password)) {
        return false;
    }
    $req = db()->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $req->execute([$email]);
    $id = $req->fetchColumn(0);

    // Stockage de l'ID et de l'email
    $_SESSION['userid'] = $id;
    $_SESSION['email'] = $_POST['email'];

    return true;
}

function inscription()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']) && isset($_POST['password']) && isset($_POST['ConfirmPassword'])) {
        if ($_POST['password'] == $_POST['ConfirmPassword']) {
            $verif = db()->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
            $verif->execute([$_POST['email']]);
            if ($verif->fetchColumn() > 0) {
                return "Email déjà utilisé";
            }
            $ajout = db()->prepare("INSERT INTO utilisateurs (email, prenom, nom, numero_tel, mot_de_passe, date_inscription) VALUES (?, ?, ?, ?, ?, NOW())");
            $ajout->execute([$_POST['email'], $_POST['prenom'], $_POST['nom'], $_POST['telephone'], md5($_POST['password'])]);
            return true;
        } else {
            return "Mots de passe ne correspondent pas";
        }
    }
    return false;
}
