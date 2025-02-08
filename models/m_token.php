<?php
require 'database.php';

// Fungsi untuk menghasilkan token random untuk "Remember Me"
function generateRememberMeToken() {
    return bin2hex(random_bytes(16));
}

// Fungsi untuk menyimpan token di database
function storeRememberMeToken($userId, $rememberToken) {
    global $pdo;

    // Token memiliki tanggal kedaluwarsa, misalnya 30 hari
    $expiresAt = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 30));  // 30 hari ke depan
    
    // Menyimpan token di database
    $sql = "INSERT INTO user_tokens (user_id, remember_token, expires_at) VALUES (:user_id, :remember_token, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':remember_token', $rememberToken);
    $stmt->bindParam(':expires_at', $expiresAt);
    
    return $stmt->execute();
}

// Fungsi untuk memverifikasi token yang ada di cookie
function verifyRememberMeToken($rememberToken) {
    global $pdo;

    // Mengecek token yang ada di database dan apakah token belum expired
    $sql = "SELECT user_id FROM user_tokens WHERE remember_token = :remember_token AND expires_at > NOW() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':remember_token', $rememberToken);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];  // Mengembalikan ID pengguna jika token valid
    }
    return false;  // Token tidak valid atau expired
}

function deleteRememberMeToken($userId) {
    global $pdo;
    $sql = "DELETE FROM user_tokens WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    return $stmt->execute();
}

function deleteExpiredToken($userId) {
    global $pdo;
    $sql = "DELETE FROM user_tokens WHERE user_id = :user_id AND expires_at < NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

function handleExpiredToken($userId){
    // Menghapus token yang sudah kedaluwarsa
    if (deleteExpiredToken($userId)) {
        // Menghapus cookie remember_token
        setcookie("remember_token", "", time() - 3600, "/");

        // Logout: Menghapus session dan cookie untuk logout
        session_unset();
        session_destroy();

        // Menghentikan eksekusi untuk memastikan logout selesai
        exit();
    }
}

// Fungsi untuk login otomatis menggunakan token
function loginWithRememberToken($rememberToken) {
    // Verifikasi token di database dan mendapatkan userId
    $userId = verifyRememberMeToken($rememberToken);

    if ($userId) {
        // Pengecekan apakah token sudah kedaluwarsa
        handleExpiredToken($userId);

        // Jika token valid, login otomatis dan set session
        $_SESSION['user_id'] = $userId;

        // Ambil informasi user berdasarkan ID
        $user = getUserById($userId);
        $_SESSION['email'] = $user['email'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['foto'] = $user['foto'];
    } else {
        // Token invalid atau sudah kedaluwarsa, logout dan hapus cookie
        setcookie("remember_token", "", time() - 3600, "/");

        // Logout: Menghapus session dan cookie untuk logout
        session_unset();
        session_destroy();

        // Menghentikan eksekusi untuk memastikan logout selesai
        exit();
    }
}