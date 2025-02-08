<?php
require '../models/m_user.php';
require 'lib/upload.php';

$body = json_decode(file_get_contents('php://input'), true);

// Ambil data dari form
$id = ($body['id']) ? $body['id'] : $_POST['id'];
$email = ($body['email']) ? $body['email'] : $_POST['email'];
$nama = ($body['nama']) ? $body['nama'] : $_POST['nama'];
$password = $body['password'] ? $body['password'] : $_POST['password'];
$notelp = ($body['notelp']) ? $body['notelp'] : $_POST['notelp'];
$status_langganan = ($body['status_langganan']) ? $body['status_langganan'] : $_POST['status_langganan'];
$waktu_daftar = ($body['waktu_daftar']) ? $body['waktu_daftar'] : $_POST['waktu_daftar'];
$role = ($body['role']) ? $body['role'] : $_POST['role'];

// Upload file dan dapatkan path file
if($_FILES['foto'] != null){ 
    $fotoPath = uploadFoto($_FILES['foto'], "assets/images/profile/");
} else {
    $fotoPath = null;
}

$data = [
    'id' => $id,
    'email' => $email,
    'nama' => $nama,
    'password' => $password, // Kirim password dalam bentuk asli
    'notelp' => $notelp,
    'status' => 'aktif',
    'status_langganan' => $status_langganan,
    'foto' => $fotoPath,
    'waktu_daftar' => $waktu_daftar,
    'role' => $role
];

// Kirim data ke API
$response = sendDataToApi($data, $base_url.'/api/update_user.php', 'POST');

// Menangani respons dari API
handleApiResponse($response, '/data_users.php');