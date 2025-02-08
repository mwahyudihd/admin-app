<?php
require '../models/m_payment.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'insert') {
    $body = json_decode(file_get_contents('php://input'), true);

    try {
        $data = [
            "atas_nama" => (isset($body["atas_nama"])) ? $body['atas_nama'] : "",
            "nomor" => (isset($body["nomor"])) ?  $body["nomor"] : "",
            "metode" => (isset($body["metode"])) ? strtoupper($body['metode']) : ""
        ];
        $result = insertData($data);
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getAll') {
    try {
        $result = getAll();
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'listMethod') {
    try {
        $result = getMethod();
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'findOne' && isset($_GET['id'])) {
    try {
        $result = findOne($_GET['id']);
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'findMethod' && isset($_GET['method'])) {
    try {
        $result = getPaymentByMethod($_GET['method']);
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {
    try {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = update($_GET['id'], $body);
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "error" => $err->getMessage()
        ]);
    }
}