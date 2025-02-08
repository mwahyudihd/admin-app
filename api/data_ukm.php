<?php
header('Content-Type: application/json');
include '../models/m_ukm.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = (isset($_GET['entries'])) ? (int)$_GET['entries'] : 10;
    $offset = (isset($_GET['page'])) ? (int)$_GET['page'] * 10 : 0;
    
    if ($limit > 0) {
        $users_data = getAllUKM($offset, $limit);
    }

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
        'email' => (isset($body['email'])) ? $body['email'] : "",
        'nama' => (isset($body['nama'])) ? $body['nama'] : "",
        'password' => (isset($encryptPass)) ? $encryptPass : "",
        'notelp' => (isset($body['notelp'])) ? $body['notelp'] : "",
        'status_langganan' => (isset($body['status_langganan'])) ? $body['status_langganan'] : "",
        'waktu_daftar' => (isset($body['waktu_daftar'])) ? $body['waktu_daftar'] : "",
        'foto' => (isset($body['foto'])) ? $body['foto'] : ""
    ];

    
    if (empty($data['email']) || empty($data['nama']) || empty($data['notelp'])) {
        http_response_code(400);
        echo json_encode([
            "statusCode" => 400,
            "message" => "Email, nama, and notelp are required fields."
        ]);
    }else{
        $insertedUKM = insertUKM($data);
        if ($insertedUKM) {
            echo json_encode([
                "statusCode" => 200,
                "message" => "New ukm added",
                "data" => $data
            ]);
        } else{
            http_response_code(500);
            echo json_encode([
                "statusCode" => 500,
                "message" => "Sory, something wrong!",
                "data" => $insertedUKM
            ]);
        }
    }
}