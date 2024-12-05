<?php
include('controllers/base.php');

session_start(); // Pastikan session dimulai sebelum melakukan pengecekan session

// Memeriksa apakah user sudah login dengan memeriksa session
if (!isset($_SESSION['role'])) {
    redirectTo('/login.php'); // Jika belum login, redirect ke halaman login
} else {
    // Halaman yang memerlukan login, bisa ditampilkan
    echo "Selamat datang, " . $_SESSION['nama'];
    redirectTo('/dashboard.php');
}