<?php
header('Content-Type: application/json');
include '../models/m_ukm.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $likeWord = ($_GET['q']) ? (string)$_GET['q'] : '';
    $likeWord = filter_var($likeWord, FILTER_SANITIZE_STRING);

    try {
        $data = searchUKM($likeWord);
        
        if (empty($data)) {
            http_response_code(404); // Not Found jika tidak ada data
            echo json_encode(['statusCode' => 404, 'message' => 'No UKM found']);
        } else {
            http_response_code(200); // OK jika data ditemukan
            echo json_encode($data);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'statusCode' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ]);
    }
}