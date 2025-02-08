<?php
$host = "localhost"; 
$username = "why2001";  
$password = "111101";      
$dbname = "db_ulbi";

// Create Connetion to databse
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}