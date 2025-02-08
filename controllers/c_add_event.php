<?php
require '../models/m_event.php';
require 'lib/upload.php';

// Upload file dan dapatkan path file
$fotoPath = uploadFoto($_FILES['foto'], "assets/images/events/");

// Menyusun data untuk dikirim ke API
$data = [
    'judul' => $_POST['judul'],
    'ukm' => $_POST['ukm'],
    'notelp' => $_POST['notelp'],
    'tanggal_acara' => $_POST['tanggal_acara'],
    'waktu_acara' => $_POST['waktu_acara'],
    'lokasi_acara' => $_POST['lokasi_acara'],
    'harga_tiket' => $_POST['harga_tiket'],
    'deskripsi' => $_POST['deskripsi'],
    'publish' => 0,
    'foto' => $fotoPath
];

// Kirim data ke API
$response = sendDataToApi($data, $base_url.'/api/data_event.php?action=insert', 'POST');

// Menangani respons dari API
handleApiResponse($response, '/data_event.php');