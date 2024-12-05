<?php
include('../models/m_user.php');
include('base.php');

$email = $_POST['email'];
$pass = $_POST['password'];

// Validasi input
if (empty($email) || empty($pass)) {
    echo "Email dan password tidak boleh kosong.";
    redirectTo('/login.php');
}

if (loginUser($email, $pass)) {
    redirectTo('/dashboard.php'); // Mengalihkan ke dashboard setelah login berhasil
} else {
    redirectTo('/login.php'); // Mengalihkan kembali ke halaman login jika login gagal
}