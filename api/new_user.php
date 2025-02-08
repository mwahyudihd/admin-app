<?php
header('Content-Type: application/json');
include '../models/m_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    $notId = ($_SESSION['user_id']) ? $_SESSION['user_id'] : $_GET['id_user'];

    $users = newUser($notId);

    if ($users !== false) {
        echo json_encode([
            'statusCode' => 200,
            'message' => 'Success',
            'total_users' => count($users),
            'data' => $users
        ]);
    } else {
        // Jika tidak ada data ditemukan atau terjadi kesalahan
        echo json_encode([
            'statusCode' => 404,  // Data tidak ditemukan
            'message' => 'No data found'
        ]);
    }
} else {
    echo json_encode([
        'statusCode' => 405,
        'message' => 'Invalid request method'
    ]);
}