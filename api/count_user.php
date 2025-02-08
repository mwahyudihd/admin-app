<?php
header('Content-Type: application/json');
include '../models/m_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['n_role'])) {
        http_response_code(400); // Bad Request
        echo json_encode([
            "statusCode" => 400,
            "message" => "Parameter 'n_role' is required"
        ]);
        exit();
    }

    $isNotCondition = $_GET['n_role'];

    try {
        $users_count = getCountUser($isNotCondition);

        http_response_code(200);
        echo json_encode([
            "statusCode" => 200,
            "message" => "User count retrieved successfully",
            "data" => $users_count
        ]);
    } catch (\Throwable $err) {
        http_response_code(500);
        echo json_encode([
            "statusCode" => 500,
            "message" => "Something went wrong",
            "error" => $err->getMessage()
        ]);
    }
}