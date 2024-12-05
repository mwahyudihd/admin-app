<?php
include('../controllers/base.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="text-center p-8 bg-white rounded-lg shadow-xl">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-4">Halaman yang Anda cari tidak ditemukan.</p>
        <p class="text-lg text-gray-500 mb-6">Mungkin halaman tersebut telah dihapus atau URL-nya salah.</p>
        <a href="<?= baseUrl() ?>" class="text-blue-500 hover:text-blue-700 font-semibold text-lg">Kembali ke halaman utama</a>
    </div>

</body>
</html>
