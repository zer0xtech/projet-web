<?php
session_start();
require_once 'datab_web.php';

if (!isset($_SESSION['userid'])) {
    header('Location: login_web.php');
    exit;
}

$pdo = db();
$userId = $_SESSION['userid'];

if (isset($_GET['id'])) {
    $annonceId = $_GET['id'];

    $verif = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE id_annonce = ? AND id_user = ?");
    $verif->execute([$annonceId, $userId]);

    if ($verif->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO favoris (id_annonce, id_user) VALUES (?, ?)");
        $stmt->execute([$annonceId, $userId]);
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
