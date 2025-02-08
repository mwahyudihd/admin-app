<?php
require 'database.php';

function getAllEvent($ofset, $limit) {
    global $pdo;

    $sql = "SELECT * FROM events LIMIT :limit OFFSET :ofset";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':ofset', $ofset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

function findAllEvents() {
    global $pdo;

    $sql = "SELECT * FROM events WHERE tanggal_acara > NOW() AND publish = 1 ORDER BY tanggal_acara DESC";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}


function insertEvent($judul, $ukm, $notelp, $tanggal_acara, $waktu_acara, $lokasi_acara, $harga_tiket, $deskripsi, $publish, $foto) {
    global $pdo;

    $stmt = $pdo->prepare("INSERT INTO events (judul, ukm, notelp, tanggal_acara, waktu_acara, lokasi_acara, harga_tiket, deskripsi, publish, foto) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$judul, $ukm, $notelp, $tanggal_acara, $waktu_acara, $lokasi_acara, $harga_tiket, ($deskripsi) ? $deskripsi : null, $publish, $foto ]);
    return $pdo->lastInsertId();
}

function findOneEvent($id) {
    global $pdo;
    $sql = "SELECT * FROM events WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteOneEvent($id) {
    global $pdo;
    $sql = "DELETE FROM events WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function searchEvent($query) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE judul LIKE ?");
    $stmt->execute(["%$query%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update publish status in the database
function updatePublish($id, $publish) {
    global $pdo;
    $query = "UPDATE events SET publish = :publish WHERE id = :id";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam("id", $id);
    $stmt->bindParam("publish", $publish);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}