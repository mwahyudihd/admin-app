<?php
require 'database.php';

function insertData($data) {
    global $pdo;

    // Query untuk memasukkan data ke dalam tabel payment
    $query = "INSERT INTO payment (atas_nama, nomor, metode) VALUES (:atas_nama, :nomor, :metode)";

    // Prepare statement
    $stmt = $pdo->prepare($query);

    // Bind parameter dengan data yang diterima
    $stmt->bindParam(':atas_nama', $data['atas_nama'], PDO::PARAM_STR);
    $stmt->bindParam(':nomor', $data['nomor'], PDO::PARAM_STR);
    $stmt->bindParam(':metode', $data['metode'], PDO::PARAM_STR);

    // Eksekusi query
    if ($stmt->execute()) {
        return [
            "statusCode" => 200,
            "message" => "Metode pembayaran berhasil ditambahkan",
            "data" => $data
        ];
    } else {
        return [
            "statusCode" => 500,
            "message" => "Metode pembayaran gagal ditambahkan",
        ];
    }
}

function update($id, $data) {
    global $pdo;

    // Membuat query dasar
    $query = "UPDATE payment SET ";

    // Array untuk menyimpan kolom dan value yang akan diupdate
    $setFields = [];
    $params = [];

    // Periksa apakah setiap kolom ada dalam $data dan jika ada, tambahkan ke query
    if (isset($data['atas_nama'])) {
        $setFields[] = "atas_nama = :atas_nama";
        $params[':atas_nama'] = $data['atas_nama'];
    }

    if (isset($data['nomor'])) {
        $setFields[] = "nomor = :nomor";
        $params[':nomor'] = $data['nomor'];
    }

    if (isset($data['metode'])) {
        $setFields[] = "metode = :metode";
        $params[':metode'] = $data['metode'];
    }

    // Gabungkan kolom-kolom yang akan diupdate
    $query .= implode(', ', $setFields);
    $query .= " WHERE id = :id";

    // Tambahkan ID ke parameter untuk WHERE clause
    $params[':id'] = $id;

    // Prepare statement
    $stmt = $pdo->prepare($query);

    // Eksekusi query dengan parameter yang sudah dipersiapkan
    if ($stmt->execute($params)) {
        return [
            "statusCode" => 200,
            "message" => "Metode pembayaran berhasil diperbarui",
            "data" => $data
        ];
    } else {
        return [
            "statusCode" => 500,
            "message" => "Metode pembayaran gagal diperbarui",
        ];
    }
}

function getAll() {
    global $pdo;

    // Query untuk mengambil semua data dari tabel payment
    $query = "SELECT * FROM payment";

    // Prepare statement
    $stmt = $pdo->prepare($query);

    // Eksekusi query
    if ($stmt->execute()) {
        // Mengambil semua data sebagai array asosiatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "statusCode" => 200,
            "message" => "Data pembayaran berhasil diambil",
            "data" => $data
        ];
    } else {
        return [
            "statusCode" => 500,
            "message" => "Gagal mengambil data pembayaran"
        ];
    }
}

function findOne($id) {
    global $pdo;

    // Query untuk mengambil data berdasarkan ID dari tabel payment
    $query = "SELECT * FROM payment WHERE id = :id";

    // Prepare statement
    $stmt = $pdo->prepare($query);

    // Bind parameter ID
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Eksekusi query
    if ($stmt->execute()) {
        // Mengambil satu data sebagai array asosiatif
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return [
                "statusCode" => 200,
                "message" => "Data pembayaran berhasil ditemukan",
                "data" => $data
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "Data pembayaran tidak ditemukan"
            ];
        }
    } else {
        return [
            "statusCode" => 500,
            "message" => "Gagal mengambil data pembayaran"
        ];
    }
}

function getMethod(){
    global $pdo;

    $query = "SELECT metode FROM payment";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_COLUMN);
    try {
        return [
            "statusCode" => 200,
            "message" => "get list method",
            "data" => $data
        ];
    } catch (Exception $err) {
        return [
            "statusCode" => 500,
            "message" => "Error",
            "error" => $err->getMessage()
        ];
    }
}

function getPaymentByMethod($method) {
    global $pdo;

    $query = "SELECT * FROM payment WHERE metode = :method";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':method', $method);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    try {
        return [
            "statusCode" => 200,
            "message" => "Success geting payement method",
            "data" => $data
        ];
    } catch (Exception $err) {
        return [
            "statusCode" => 500,
            "message" => "Error",
            "error" => $err->getMessage()
        ];
    }
}

function delPaymentMethod($id) {
    global $pdo;
    $sql = "DELETE FROM payment WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}