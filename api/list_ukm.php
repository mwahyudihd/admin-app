<?php
require '../models/m_ukm.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['getName'])) {
    try {
        $data = getListUKM();
        http_response_code(200);
        echo json_encode(["statusCode" => 200, "message" => "List Nama berhasil diambil", "data" => $data ]);
    } catch (Exception $expt) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server error!", "error" => $expt->getMessage()]);
    }
}