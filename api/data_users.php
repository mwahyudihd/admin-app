<?php
include '../models/m_user.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    
    $notId =  ($_GET['id_user']) ? $_GET['id_user'] : $_SESSION['user_id'];
    
    $limit = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
    $offset = isset($_GET['page']) ? (int)$_GET['page'] * 10 : 0;
    
    if ($limit > 0) {
        $users_data = getAllUsers($notId, $offset, $limit);
    }
    
    // Mengembalikan data dalam format JSON
    http_response_code(200);
    echo json_encode($users_data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    if (empty($body['password'])) {
        http_response_code(400);
        echo json_encode([
            "statusCode" => 400,
            "message" => "Password is required"
        ]);
        exit();
    } else {
        $encryptPass = password_hash($body['password'], PASSWORD_BCRYPT);
    }

    $data = [
        'email' => ($body['email']) ? $body['email'] : "",
        'nama' => ($body['nama']) ? $body['nama'] : "",
        'password' => ($encryptPass) ? $encryptPass : "",
        'notelp' => ($body['notelp']) ? $body['notelp'] : "",
        'status' => 'aktif',
        'status_langganan' => ($body['status_langganan']) ? $body['status_langganan'] : "Regular",
        'foto' => ($body['foto']) ? $body['foto'] : null,
        'waktu_daftar' => ($body['waktu_daftar']) ? $body['waktu_daftar'] : date("Y-m-d"),
        'role' => ($body['role']) ? $body['role'] : "user"
    ];

    
    if (empty($data['email']) || empty($data['nama']) || empty($data['notelp'])) {
        http_response_code(400);
        echo json_encode([
            "statusCode" => 400,
            "message" => "Email, nama, and notelp are required fields."
        ]);
    }else{
        $insertedUser = insertUser($data);
        if ($insertedUser) {
            echo json_encode([
                "statusCode" => 200,
                "message" => "New user added",
                "data" => $data
            ]);
        } else{
            http_response_code(500);
            echo json_encode([
                "statusCode" => 500,
                "message" => "Sory, something wrong!",
                "data" => $insertedUser
            ]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
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