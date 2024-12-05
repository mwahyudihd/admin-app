<?php
include('../models/m_user.php');

$id = isset($_GET['id_user']) ? (int)$_GET['id_user'] : null;

// Jika limit diberikan (untuk input entries), ambil data berdasarkan limit
if (deleteUser($id)) {
    $response = [
        "status" => 200,
        "message" => "Success!"
    ];
    $_SESSION['status'] = 'deleted';
}else{
    $response = [
        "status" => 404,
        "message" =>'Gagal menghapus user dengan id = '.$id
    ];
    $_SESSION['status'] = 'error';
}

header('Content-Type: application/json');
echo json_encode($response);
