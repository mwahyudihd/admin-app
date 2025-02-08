<?php
include '../models/m_ukm.php';

$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;

if (deleteUKM($id)) {
    $response = [
        "status" => 200,
        "message" => "Success!"
    ];
}else{
    $response = [
        "status" => 404,
        "message" =>'Gagal menghapus ukm dengan id = '.$id
    ];
}

header('Content-Type: application/json');
echo json_encode($response);