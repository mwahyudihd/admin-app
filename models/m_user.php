<?php
require 'database.php';
require 'm_token.php';

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

function findOneUser($id) {
    global $pdo;
    $response = [
        'statusCode' => 400,
        'message' => 'Data not found or error occurred',
        'data' => null
    ];

    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Jika data ditemukan
            $response['statusCode'] = 200;
            $response['message'] = 'Data found successfully';
            $response['data'] = $result;
        } else {
            // Jika data tidak ditemukan
            $response['statusCode'] = 404;
            $response['message'] = 'Data not found';
        }
    } catch (Exception $e) {
        $response['statusCode'] = 500;
        $response['message'] = 'Internal Server Error: ' . $e->getMessage();
    }

    return json_encode($response);
}

// Fungsi untuk mengupdate data user berdasarkan ID
function updateUser($id, $data) {
    global $pdo;

    // Mulai membangun query dasar
    $sql = "UPDATE users SET email = :email, nama = :nama, notelp = :notelp, status_langganan = :status_langganan, waktu_daftar = :waktu_daftar, role = :role";

    // Menambahkan kolom foto dan password jika ada datanya
    if ($data['foto'] != null) {
        $sql .= ", foto = :foto";
    }

    if ($data['password'] != null) {
        $sql .= ", password = :password";
    }

    // Menambahkan bagian WHERE
    $sql .= " WHERE id = :id";

    // Persiapkan statement
    $stmt = $pdo->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':notelp', $data['notelp']);
    $stmt->bindParam(':status_langganan', $data['status_langganan']);
    $stmt->bindParam(':waktu_daftar', $data['waktu_daftar']);
    $stmt->bindParam(':role', $data['role']);
    $stmt->bindParam(':id', $id);

    // Bind foto dan password jika ada
    if ($data['foto'] != null) {
        $stmt->bindParam(':foto', $data['foto']);
    }

    if ($data['password'] != null) {
        $stmt->bindParam(':password', $data['password']);
    }

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
        return true;
    }else{
        return false;
    }
}

function logout() {
    session_start();  // Memulai session

    // Periksa apakah ada token "remember_token" di cookie
    if (isset($_COOKIE['remember_token'])) {
        $rememberToken = $_COOKIE['remember_token'];
        
        // Ambil user_id berdasarkan token
        $userId = verifyRememberMeToken($rememberToken);
        
        // Hapus token "remember_token" dari database
        if ($userId) {
            deleteRememberMeToken($userId);  // Hapus token dari database
        }

        // Hapus cookie
        setcookie("remember_token", "", time() - 3600, "/");  // Menghapus cookie "remember_token"
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

function login($email, $password, $rememberMe) {
    global $pdo;

    // SQL untuk mencari user berdasarkan email
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Jika login berhasil, kita akan mulai sesi dan menyimpan data pengguna
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['foto'] = $user['foto'];

        if ($user['role'] != 'admin' && $user['role'] != 'Admin') {
            $rememberMe = false;
        }
        // Jika "Remember Me" dipilih, buat token dan simpan di cookie serta database
        if ($rememberMe) {
            // Generate token "Remember Me"
            $rememberToken = generateRememberMeToken();
            storeRememberMeToken($user['id'], $rememberToken);  // Simpan token di database

            // Simpan token di cookie, berlaku selama 30 hari
            setcookie("remember_token", $rememberToken, time() + (60 * 60 * 24 * 30), "/", "", true, true);
        }

        // Mengirimkan respons login berhasil
        return [
            'statusCode' => 200,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'nama' => $user['nama'],
                'role' => $user['role'],
                'foto' => $user['foto']
            ]
        ];
    } else {
        // Jika login gagal
        return [
            'statusCode' => 400,
            'message' => 'Email atau password salah'
        ];
    }
}

function getCountUser($notCondition){
    global $pdo;
    $query = "SELECT COUNT(*) FROM users WHERE status != :notCondition";
    $data = $pdo->prepare($query);
    $data->bindParam(':notCondition', $notCondition);
    $data->execute();
    $count = $data->fetchColumn();
    return $count;
}

function searchUser($like, $not){
    global $pdo;
    $likeParam = '%' . $like . '%';

    $query = "SELECT * FROM users WHERE nama LIKE :likeParam AND id != :notUser";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':likeParam', $likeParam, PDO::PARAM_STR);
    $stmt->bindParam(':notUser', $not, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function newUser($notId) {
    global $pdo;

    $query = "SELECT * FROM users WHERE created_at >= NOW() - INTERVAL 5 DAY AND id != :user";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user', $notId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }

}

function updateFoto($email, $newFoto){
    global $pdo;

    $query = "UPDATE users SET foto = :newFoto WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':newFoto', $newFoto);
    if ($email == null || $newFoto == null) {
        return [
            "statusCode" => 400,
            "message" => "Email or foto is required"
        ];
    }else {
        try {
            $stmt->execute();
            return [
                "statusCode" => 200,
                "message" => "Update foto success!",
                "data" => $newFoto
            ];
        } catch (Exception $err) {
            return [
                "statusCode" => 500,
                "message" => "Internal server error!",
                "error" => $err
            ];
        }
    }
}

function updatePassOrName($username, $password, $email) {
    global $pdo;

    // Mulai query update
    $query = "UPDATE users SET nama = :username WHERE email = :email";

    // Jika password ada, tambahkan ke query
    if (!empty($password) && $password != null) {
        $query = "UPDATE users SET nama = :username, password = :password WHERE email = :email";
    }

    $stmt = $pdo->prepare($query);

    // Bind parameter
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    
    if (!empty($password)) {
        $stmt->bindParam(':password', $password);
    }

    try {
        // Eksekusi query
        $stmt->execute();

        // Menyusun response berdasarkan apakah password diperbarui
        if (!empty($password)) {
            return [
                "statusCode" => 200,
                "message" => "Username and Password have been changed!",
                "data" => $username
            ];
        }

        return [
            "statusCode" => 200,
            "message" => "Username has been changed!",
            "data" => $username
        ];
    } catch (PDOException $err) {
        return [
            "statusCode" => 500,
            "message" => "Something went wrong!",
            "error" => $err->getMessage() // Menampilkan pesan error yang lebih jelas
        ];
    }
}