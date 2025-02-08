<?php
include '../models/m_user.php';
include '../controllers/lib/base.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data JSON dari body request
    $data = json_decode(file_get_contents("php://input"), true);

    // Memeriksa apakah data yang dibutuhkan ada dalam request
    if (isset($data['email']) && isset($data['password'])) {
        // Mendapatkan nilai email, password, dan remember_me dari request
        $email = $data['email'];
        $password = $data['password'];
        $rememberMe = (isset($data['remember_me'])) ? $data['remember_me'] : false;

        // Menjalankan fungsi login
        $response = login($email, $password, $rememberMe);

        // Mengirimkan respons sesuai dengan hasil login
        if ($response['statusCode'] === 200) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code($response['statusCode']); // Jika login gagal, menggunakan status code yang sesuai
            echo json_encode($response);
        }
    } else {
        // Jika email atau password tidak diberikan dalam request
        echo json_encode([
            'statusCode' => 400,
            'message' => 'Email dan password diperlukan'
        ]);
        http_response_code(400);
    }
} else {
    // Jika metode selain POST digunakan
    echo json_encode([
        'statusCode' => 405,
        'message' => 'Metode request tidak valid'
    ]);
    http_response_code(405);
}