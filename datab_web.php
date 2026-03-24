<?php
session_start();

function db(): ?PDO
{
    try {
        return new PDO(
            'mysql:host=127.0.0.1;dbname=projet-web;charset=utf8',
            'root',
            ''
        );
    } catch (Exception $e) {
        echo "Erreur de connexion à la BDD";
        return null;
    }
}
