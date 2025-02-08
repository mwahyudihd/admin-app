<?php
require '../models/m_transaksi.php';
require 'lib/upload.php';

$data = [
    'atas_nama' => $_POST['atas_nama'],
    'nomor' => $_POST['nomor'],
    'metode' => $_POST['metode']
];

$response = sendDataToApi($data, $base_url.'/api/payment.php?action=insert', 'POST');

handleApiResponse($response, '/payment_settings.php');