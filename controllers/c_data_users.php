<?php
include('../models/m_user.php');
session_start();

// Mendapatkan ID pengguna yang sedang login
$notId = $_SESSION['user_id'];

// Menangani GET parameter 'entries' untuk menentukan limit data
$limit = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;  // Default ke 10 jika tidak ada input 'entries'
$offset = isset($_GET['page']) ? (int)$_GET['page'] * 10 : 0;    // Offset untuk pagination

// Jika limit diberikan (untuk input entries), ambil data berdasarkan limit
if ($limit > 0) {
    $users_data = getAllUsers($notId, $offset, $limit);
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($users_data);
