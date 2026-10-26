<?php
/**
 * Connexion à la base de données MySQL via PDO
 */
$host = 'localhost';
$dbname = 'djorro_restaurant';
$username = 'root'; // Changez selon votre hébergeur / MAMP / XAMPP
$password = '';     // Changez selon votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // En production, ne jamais afficher l'erreur exacte à l'utilisateur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}