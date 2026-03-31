<?php
require_once './datab_web.php';

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
            $ajout = db()->prepare("INSERT INTO users (email, prenom, nom, phone, password, ville, date_inscription) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $ajout->execute([$_POST['email'], $_POST['prenom'], $_POST['nom'], $_POST['telephone'], md5($_POST['password']), $_POST['ville'] ?? '']);
            return true;
        } else {
            return "Mots de passe ne correspondent pas";
        }
    }
    return false;
}

function publication($categorieChoisie)
{
    if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['state']) && isset($_POST['category2']) && isset($_FILES['file']) && isset($_SESSION['userid'])) {

        $title = $_POST['title'];
        $description = htmlspecialchars($_POST['description']);
        $price = $_POST['price'];
        $state = $_POST['state'];
        $category2 = $_POST['category2'];

        $req = db()->prepare("SELECT ville from users WHERE id = ?");
        $id = $_SESSION['userid'];
        $req->execute([$id]);
        $ville = $req->fetchColumn(0);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $files = $_FILES['file'];
        $new_Names = [];

        if (!is_array($files['name'])) {
            $files['name']     = [$files['name']];
            $files['error']    = [$files['error']];
            $files['size']     = [$files['size']];
            $files['tmp_name'] = [$files['tmp_name']];
        }

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] == 0 && $files['size'][$index] <= 5000000) {
                $fileInfo = pathinfo($name);
                $extension = strtolower($fileInfo['extension']);

                if (in_array($extension, $allowedExtensions)) {
                    $new_Name = "images/" . uniqid() . '.' . $extension;

                    if (move_uploaded_file($files['tmp_name'][$index], $new_Name)) {
                        $new_Names[] = $new_Name;
                    }
                }
            }
        }
        if (!empty($new_Names)) {
            $url_photos = implode(',', $new_Names);
            $req = db()->prepare("INSERT INTO annonces (categorie, sous_categorie, user_id, titre, description, ville, prix, etat, url_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $req->execute([$categorieChoisie, $category2, $id, $title, $description, $ville, $price, $state, $url_photos]);
            header("Location: " . 'index.php' . "?success=1");
            exit();
        }
    }
}

function publication_ia($categorieChoisie)
{
    if (isset($_POST['title']) && isset($_POST['reponse']) && isset($_POST['price']) && isset($_POST['state']) && isset($_POST['category2']) && isset($_FILES['file']) && isset($_SESSION['userid'])) {

        $title = $_POST['title'];
        $description = htmlspecialchars($_POST['reponse']);
        $price = $_POST['price'];
        $state = $_POST['state'];
        $category2 = $_POST['category2'];

        $req = db()->prepare("SELECT ville from users WHERE id = ?");
        $id = $_SESSION['userid'];
        $req->execute([$id]);
        $ville = $req->fetchColumn(0);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $files = $_FILES['file'];
        $new_Names = [];

        if (!is_array($files['name'])) {
            $files['name']     = [$files['name']];
            $files['error']    = [$files['error']];
            $files['size']     = [$files['size']];
            $files['tmp_name'] = [$files['tmp_name']];
        }

        foreach ($files['name'] as $index => $name) {
            if ($files['error'][$index] == 0 && $files['size'][$index] <= 5000000) {
                $fileInfo = pathinfo($name);
                $extension = strtolower($fileInfo['extension']);

                if (in_array($extension, $allowedExtensions)) {
                    $new_Name = "images/" . uniqid() . '.' . $extension;

                    if (move_uploaded_file($files['tmp_name'][$index], $new_Name)) {
                        $new_Names[] = $new_Name;
                    }
                }
            }
        }
        if (!empty($new_Names)) {
            $url_photos = implode(',', $new_Names);
            $req = db()->prepare("INSERT INTO annonces (categorie, sous_categorie, user_id, titre, description, ville, prix, etat, url_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $req->execute([$categorieChoisie, $category2, $id, $title, $description, $ville, $price, $state, $url_photos]);
            header("Location: " . 'index.php' . "?success=1");
            exit();
        }
    }
}

function est_admin()
{
    $id = $_SESSION['userid'];
    $req = db()->prepare("SELECT role FROM users WHERE id = ?");
    $req->execute([$id]);
    $role = $req->fetchColumn(0);
    return $role;
}
