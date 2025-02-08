<?php
include '../models/m_transaksi.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['ticket']) && $_GET['ticket'] == 'active' && isset($_GET['email'])) {
    try {
        $response = getHistori($_GET['email'], 'getActive', null);
        http_response_code($response['statusCode']);
        echo json_encode($response);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error", "error" => $th->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['history']) && isset($_GET['email'])) {
    try {
        $response = getHistori($_GET['email'], 'allHistory', null);
        http_response_code($response['statusCode']);
        echo json_encode($response);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error", "error" => $th->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['status']) && isset($_GET['email'])) {
    try {
        $response = getHistori($_GET['email'], 'withStatus', $_GET['status']);
        http_response_code($response['statusCode']);
        echo json_encode($response);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error", "error" => $th->getMessage()]);
    }
}