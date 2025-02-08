<?php
require "../models/m_user.php";

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] == 'setImage' && isset($_GET['email'])) {
    file_put_contents('log.txt', json_encode($_FILES), FILE_APPEND);
    file_put_contents('log.txt', json_encode($_POST), FILE_APPEND);

    function uploadFoto($file, $path) {
        $targetDir = $path;
        $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
        $randomFileName = uniqid() . '.' . $fileExt;
        $targetFilePath = $targetDir . $randomFileName;
        $targetFilePathData = '../' . $targetFilePath;
        if (!move_uploaded_file($file["tmp_name"], $targetFilePathData)) {
            throw new Exception("Failed to move uploaded file.");
        }
        return $targetFilePath;
    }

    try {
        // Pastikan email dan file gambar ada

        $email = $_GET['email'];

        // Validasi file gambar
        if ($_FILES['foto']['error'] != UPLOAD_ERR_OK) {
            throw new Exception("Gagal mengupload gambar.");
        }

        // Upload foto menggunakan fungsi uploadFoto
        $fotoPath = uploadFoto($_FILES['foto'], "assets/images/profile/");

        // Update path foto di database
        $result = updateFoto($email, $fotoPath);

        // Mengirimkan response sesuai dengan hasil
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        // Jika terjadi error, kirimkan response error
        http_response_code(505);
        echo json_encode([
            "statusCode" => 505,
            "error" => "Something Wrong! " . $err->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['action']) && $_GET['action'] == 'edit') {
    $body = json_decode(file_get_contents('php://input'), true);
    try {
        $email = (isset($body['email'])) ? $body['email'] : '';
        $name = (isset($body['nama'])) ? $body['nama'] : '';
        $pass = (isset($body['password']) && !empty($body['password'])) ? password_hash($body['password'], PASSWORD_BCRYPT) : null;
        $result = updatePassOrName($name, $pass, $email);
        http_response_code($result["statusCode"]);
        echo json_encode($result);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal server error", "error" => $err->getMessage()]);
    }
}
