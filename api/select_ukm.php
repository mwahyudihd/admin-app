<?php
header('Content-Type: application/json');
include '../models/m_ukm.php';

function sendResponse($statusCode, $message, $data = null) {
    http_response_code($statusCode);
    echo json_encode([
        'statusCode' => $statusCode,
        'message' => $message,
        'data' => $data
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $getId = (isset($_GET['id'])) ? $_GET['id'] : null;

    if (!$getId) {
        sendResponse(400, 'ID is required');
        return;
    }

    if (!is_numeric($getId)) {
        sendResponse(400, 'Invalid ID format');
        return;
    }

    $getData = findOneUKM($getId);
    $getDataDecoded = json_decode($getData, true);

    switch ($getDataDecoded['statusCode']) {
        case 200:
            sendResponse(200, 'Data found successfully', $getDataDecoded['data']);
            break;

        case 400:
            sendResponse(400, 'Invalid ID format or data error');
            break;

        case 404:
            sendResponse(404, 'Data not found');
            break;

        case 500:
            sendResponse(500, 'Internal Server Error: ' . $getDataDecoded['message']);
            break;

        default:
            sendResponse(500, 'Unexpected error occurred');
            break;
    }
}