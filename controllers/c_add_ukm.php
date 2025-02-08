<?php
require '../models/m_ukm.php';
require 'lib/upload.php';

// Ambil data dari form
$email = $_POST['email'];
$nama = $_POST['nama'];
$password = $_POST['password']; // Password belum dienkripsi
$notelp = $_POST['notelp'];
$status_langganan = $_POST['status_langganan'];
$waktu_daftar = $_POST['waktu_daftar'];

// Upload file dan dapatkan path file
$fotoPath = uploadFoto($_FILES['foto'], "assets/images/ukm/");

// Menyusun data untuk dikirim ke API
$data = [
    'email' => $email,
    'nama' => $nama,
    'password' => $password, // Kirim password dalam bentuk asli
    'notelp' => $notelp,
    'status_langganan' => $status_langganan,
    'foto' => $fotoPath,
    'waktu_daftar' => $waktu_daftar,
];

// Kirim data ke API
$response = sendDataToApi($data, $base_url.'/api/data_ukm.php', 'POST');

// Menangani respons dari API
handleApiResponse($response, '/data_ukm.php');