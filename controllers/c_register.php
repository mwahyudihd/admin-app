<?php
include('../models/m_user.php');
include('base.php');

$preEncrypt = $_POST['password'];

$encryptPass = password_hash($preEncrypt, PASSWORD_BCRYPT);

$targetDir = "assets/images/profile/";
$fileName = basename($_FILES['foto']["name"]);
$targetFilePath = $targetDir . $fileName;
$targetFilePathData = '../'.$targetDir . $fileName;
$fileType = $_FILES['foto']['type'];
$file_size = $_FILES['foto']['size'];

move_uploaded_file($_FILES['foto']["tmp_name"], $targetFilePathData);

$data = [
    'email' => $_POST['email'],
    'nama' => $_POST['nama'],
    'password' => $encryptPass,
    'notelp' => $_POST['notelp'],
    'status' => 'aktif',
    'status_langganan' => $_POST['status_langganan'],
    'foto' => $targetFilePath,
    'waktu_daftar' => $_POST['waktu_daftar'],
    'role' => $_POST['role']
];

// Panggil fungsi insertUser dari fungsi
$insertedId = insertUser($data);

if ($insertedId) {
    echo "Data berhasil disimpan dengan ID: " . $insertedId;
    redirectToWith('/data_users.php','success');
} else {
    redirectToWith('/data_users.php', 'error');
}