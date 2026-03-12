<?php
$host = 'localhost';
$dbname = 'u465319993_euroavia';
$user = 'u465319993_euroavia'; 
$pass = 'Euroavia2000';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Eroare conectare: " . $e->getMessage());
}
?>
