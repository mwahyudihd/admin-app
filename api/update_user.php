<?php
header('Content-Type: application/json');
include '../models/m_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    // Validasi data
    if (empty($body['id']) || empty($body['email']) || empty($body['nama']) || empty($body['notelp'])) {
        http_response_code(400);
        echo json_encode(['statusCode' => 400, 'message' => 'Required fields are missing']);
        exit;
    }

    // Mengambil data user berdasarkan ID
    $old_user = getUserById($body['id']);

    if (!$old_user) {
        http_response_code(404);
        echo json_encode(['statusCode' => 404, 'message' => 'User not found']);
        exit;
    }

    // Memeriksa apakah password baru sama dengan password lama
    if ($old_user['password'] === $body['password']) {
        $pass = null;  // Jika password sama, tidak perlu memperbarui password
    } else {
        $pass = password_hash($body['password'], PASSWORD_BCRYPT); // Hash password baru
    }

    // Menyusun data untuk update
    $data = [
        'email' => $body['email'],
        'nama' => $body['nama'],
        'password' => $pass,
        'notelp' => $body['notelp'],
        'status_langganan' => $body['status_langganan'],
        'foto' => $body['foto'],
        'waktu_daftar' => $body['waktu_daftar'],
        'role' => $body['role']
    ];

    // Melakukan update data user
    $execute = updateUser($body['id'], $data);

    if ($execute) {
        http_response_code(200);
        echo json_encode(['statusCode' => 200, 'message' => 'User is updated']);
    } else {
        http_response_code(500); // Server error jika gagal update
        echo json_encode(['statusCode' => 500, 'message' => 'User is not updated']);
    }
}