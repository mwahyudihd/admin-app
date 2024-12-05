<?php
$host = "localhost"; // Ganti dengan alamat host MySQL jika tidak di localhost
$username = "why2001";  // Ganti dengan username MySQL Anda
$password = "111101";      // Ganti dengan password MySQL Anda
$dbname = "db_ulbi"; // Nama database

// Membuat koneksi
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}