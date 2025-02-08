<?php
include '../models/m_user.php';

$id = (isset($_GET['id_user'])) ? (int)$_GET['id_user'] : null;

if (deleteUser($id)) {
    $response = [
        "status" => 200,
        "message" => "Success!"
    ];
}else{
    $response = [
        "status" => 404,
        "message" =>'Gagal menghapus user dengan id = '.$id
    ];
}

header('Content-Type: application/json');
echo json_encode($response);