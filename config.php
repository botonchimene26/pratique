<?php
session_start();

$host = 'localhost';
$db   = 'anagramme_db';
$user = 'root';
$pass = ''; // sur XAMPP, par défaut vide

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>