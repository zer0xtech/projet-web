<?php
require_once 'datab_web.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

$pdo = db();
$userId = $_SESSION['userid'];

if (isset($_GET['id'])) {
    $annonceId = $_GET['id'];

    $verif = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE id_annonce = ? AND id_user = ?");
    $verif->execute([$annonceId, $userId]);

    if ($verif->fetchColumn() > 0) {
        $stmt = $pdo->prepare("DELETE FROM favoris WHERE id_annonce = ? AND id_user = ?");
        $stmt->execute([$annonceId, $userId]);
    }
}


header('Location: favoris.php');
exit;
