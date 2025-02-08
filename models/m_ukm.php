<?php
require 'database.php';

function insertUKM($data) {
    global $pdo;

    // SQL untuk melakukan insert data
    $sql = "INSERT INTO tb_ukm (email, password, nama, notelp, status_langganan, waktu_daftar,foto) 
            VALUES (:email, :password, :nama, :notelp, :status_langganan, :waktu_daftar, :foto)";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Bind parameter ke query
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':notelp', $data['notelp']);
    $stmt->bindParam(':status_langganan', $data['status_langganan']);
    $stmt->bindParam(':waktu_daftar', $data['waktu_daftar']);
    $stmt->bindParam(':foto', $data['foto']);

    // Eksekusi query
    if ($stmt->execute()) {
        return $pdo->lastInsertId();
    } else {
        return false;
    }
}

function getAllUKM($ofset, $limit) {
    global $pdo;

    $sql = "SELECT * FROM tb_ukm LIMIT :limit OFFSET :ofset";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':ofset', $ofset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

function searchUKM($like){
    global $pdo;
    $likeParam = '%' . $like . '%';

    $query = "SELECT * FROM tb_ukm WHERE nama LIKE :likeParam";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':likeParam', $likeParam, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteUKM($id) {
    global $pdo;

    // SQL untuk menghapus user berdasarkan ID
    $sql = "DELETE FROM tb_ukm WHERE id = :id";
    
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

function findOneUKM($id) {
    global $pdo;
    $response = [
        'statusCode' => 400,
        'message' => 'Data not found or error occurred',
        'data' => null
    ];

    try {
        $sql = "SELECT * FROM tb_ukm WHERE id = :id";
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

function updateUKM($id, $data) {
    global $pdo;

    // Mulai membangun query dasar
    $sql = "UPDATE tb_ukm SET email = :email, nama = :nama, notelp = :notelp, status_langganan = :status_langganan, waktu_daftar = :waktu_daftar";

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

function getUKMById($id) {
    global $pdo;

    // SQL untuk memilih data user berdasarkan ID
    $sql = "SELECT * FROM tb_ukm WHERE id = :id";
    
    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    // Eksekusi query
    $stmt->execute();
    
    // Mengambil hasil query sebagai array
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getListUKM(){
    global $pdo;

    $query = "SELECT nama FROM tb_ukm";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}