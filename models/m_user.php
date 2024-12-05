<?php
include('database.php');

// Fungsi untuk insert data user
function insertUser($data) {
    global $pdo;

    // SQL untuk melakukan insert data
    $sql = "INSERT INTO users (email, password, nama, notelp, status, status_langganan, foto, waktu_daftar, role) 
            VALUES (:email, :password, :nama, :notelp, :status, :status_langganan, :foto, :waktu_daftar, :role)";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':notelp', $data['notelp']);
    $stmt->bindParam(':status', $data['status']);
    $stmt->bindParam(':status_langganan', $data['status_langganan']);
    $stmt->bindParam(':foto', $data['foto']);
    $stmt->bindParam(':waktu_daftar', $data['waktu_daftar']);
    $stmt->bindParam(':role', $data['role']);

    // Eksekusi query
    if ($stmt->execute()) {
        return $pdo->lastInsertId(); // Mengembalikan ID dari data yang baru dimasukkan
    } else {
        return false; // Jika terjadi error
    }
}

// Fungsi untuk mendapatkan data user berdasarkan ID
function getUserById($id) {
    global $pdo;

    // SQL untuk memilih data user berdasarkan ID
    $sql = "SELECT * FROM users WHERE id = :id";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    // Eksekusi query
    $stmt->execute();
    
    // Mengambil hasil query sebagai array
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengupdate data user berdasarkan ID
function updateUser($id, $data) {
    global $pdo;

    // SQL untuk update data user berdasarkan ID
    $sql = "UPDATE users 
            SET email = :email, password = :password, nama = :nama, notelp = :notelp, status = :status, 
                status_langganan = :status_langganan, foto = :foto, waktu_daftar = :waktu_daftar, role = :role
            WHERE id = :id";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':notelp', $data['notelp']);
    $stmt->bindParam(':status', $data['status']);
    $stmt->bindParam(':status_langganan', $data['status_langganan']);
    $stmt->bindParam(':foto', $data['foto']);
    $stmt->bindParam(':waktu_daftar', $data['waktu_daftar']);
    $stmt->bindParam(':role', $data['role']);
    $stmt->bindParam(':id', $id);

    // Eksekusi query
    return $stmt->execute();
}

// Fungsi untuk menghapus user berdasarkan ID
function deleteUser($id) {
    global $pdo;

    // SQL untuk menghapus user berdasarkan ID
    $sql = "DELETE FROM users WHERE id = :id";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    // Eksekusi query
    $executed = $stmt->execute();
    if ($executed) {
        return $_SESSION['status'] = 'deleted';
    }else{
        return $_SESSION['status'] = 'error';
    }
}

function loginUser($email, $password) {
    global $pdo;
    session_start(); // Memulai session

    // SQL untuk mengambil data user berdasarkan email
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Mengambil hasil query
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Memeriksa apakah user ditemukan dan password cocok
    if ($user && password_verify($password, $user['password'])) {
        // Menyimpan informasi user ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        // Redirect ke halaman yang sesuai (misalnya dashboard)
        return true;
    } else {
        // Jika login gagal, set session error
        $_SESSION['error'] = 'Email atau password salah.';
        return false;
    }
}

function getAllUsers($notId, $ofset, $limit) {
    global $pdo;

    $sql = "SELECT * FROM users WHERE id != :notId LIMIT :limit OFFSET :ofset";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':notId', $notId, PDO::PARAM_INT);
    $stmt->bindParam(':ofset', $ofset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}