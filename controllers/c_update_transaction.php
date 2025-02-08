<?php
require '../models/m_transaksi.php';
require 'lib/upload.php';

$data = [
    'kode' => $_POST['kode'],
    'nama_customer' => $_POST['nama_customer'],
    'produk' => $_POST['produk'],
    'qty' => $_POST['qty'],
    'harga' => $_POST['harga'],
    'metode' => $_POST['metode'],
    'status' => (!empty($_POST['custom_status']) && isset($_POST['custom_status'])) ? $_POST['custom_status'] : $_POST['status'],
    'deadline' => $_POST['deadline']
];

$response = sendDataToApi($data, $base_url.'/api/data_transaksi.php?action=update&id='.$_POST['id'], 'POST');

handleApiResponse($response, '/data_transaksi.php');