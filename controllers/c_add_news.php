<?php
require '../models/m_berita.php';
require 'lib/upload.php';

// Ambil data dari form
$judul = $_POST['judul_berita'];
$deskripsi = $_POST['deskripsi'];
$creator = $_POST['creator'];
$notelp = $_POST['notelp'];

// Upload file dan dapatkan path file
$fotoPath = uploadFoto($_FILES['foto'], "assets/images/news/");

// Menyusun data untuk dikirim ke API
$data = [
    'judul_berita' => $judul,
    'deskripsi' => $deskripsi,
    'creator' => $creator,
    'notelp' => $notelp,
    'foto' => $fotoPath,
    'publish' => 0
];

// Kirim data ke API
$response = sendDataToApi($data, $base_url.'/api/data_berita.php?action=insert', 'POST');

// Menangani respons dari API
handleApiResponse($response, '/data_berita.php');