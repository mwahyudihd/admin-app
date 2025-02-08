<?php
header('Content-Type: application/json');
include '../models/m_user.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    
    // Mendapatkan ID pengguna yang sedang login
    $notId =  ($_SESSION['user_id']) ? $_SESSION['user_id'] : (($_GET['id_user']) ? $_GET['id_user'] : null); // Pastikan ada nilai fallback
    
    // Validasi jika id_user tidak ada
    if (!$notId) {
        http_response_code(400); // Bad Request jika id_user tidak ada
        echo json_encode(['statusCode' => 400, 'message' => 'User ID is required']);
        exit;
    }
    $likeWord = ($_GET['q']) ? (string)$_GET['q'] : '';
    $likeWord = filter_var($likeWord, FILTER_SANITIZE_STRING);

    try {
        // Mengambil data pengguna dengan fungsi searchUser yang telah didefinisikan
        $users_data = searchUser($likeWord, $notId);
        
        // Mengecek apakah ada data pengguna yang ditemukan
        if (empty($users_data)) {
            http_response_code(404); // Not Found jika tidak ada data
            echo json_encode(['statusCode' => 404, 'message' => 'No users found']);
        } else {
            // Mengembalikan data dalam format JSON
            http_response_code(200); // OK jika data ditemukan
            echo json_encode($users_data);
        }
    } catch (Exception $e) {
        // Menangani error dan mengembalikan pesan kesalahan
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'statusCode' => 500,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ]);
    }
}