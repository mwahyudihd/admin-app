<?php
require 'base.php';

function uploadFoto($file, $path) {
    $targetDir = $path;
    $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
    $randomFileName = uniqid() . '.' . $fileExt;
    $targetFilePath = $targetDir . $randomFileName;
    $targetFilePathData = '../' . $targetFilePath;

    move_uploaded_file($file["tmp_name"], $targetFilePathData);
    return $targetFilePath;
}

function sendDataToApi($data, $apiUrl, $method) {
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function handleApiResponse($response, $route) {
    if (isset($response['statusCode']) && $response['statusCode'] == 200) {
        // Redirect ke halaman sukses
        redirectToWith($route, 'success');
    } else {
        redirectToWith($route, 'error');
    }
}
