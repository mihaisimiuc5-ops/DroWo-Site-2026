<?php
session_start();

define('DB_HOST', 'srv1816.hstgr.io');       
define('DB_NAME', 'u465319993_inscriere');    
define('DB_USER', 'u465319993_inscriere');   
define('DB_PASS', 'EuroaviaBucuresti2026');       

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Eroare la conectarea cu baza de date: " . $e->getMessage());
}
?>