<?php
require 'database.php';

function getAllNews($ofset, $limit) {
    global $pdo;

    $sql = "SELECT * FROM berita LIMIT :limit OFFSET :ofset";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':ofset', $ofset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

function findAllNews() {
    global $pdo;

    $sql = "SELECT * FROM berita WHERE publish = 1 ORDER BY tanggal_upload DESC";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}


function insertNews($judul_berita, $creator, $notelp, $deskripsi, $foto, $publish) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO berita (judul_berita, creator, notelp, tanggal_upload, deskripsi, foto, publish) 
                           VALUES (?, ?, ?, DATE(NOW()), ?, ?, ?)");
    $stmt->execute([$judul_berita, $creator, $notelp, $deskripsi, $foto, $publish]);
    return $pdo->lastInsertId();
}

function findOneNews($id) {
    global $pdo;
    $sql = "SELECT * FROM berita WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteOneNews($id) {
    global $pdo;
    $sql = "DELETE FROM berita WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function searchNews($query) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE judul_berita LIKE ?");
    $stmt->execute(["%$query%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update publish status in the database
function updatePublish($id, $publish) {
    global $pdo;
    $query = "UPDATE berita SET publish = :publish WHERE id = :id";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam("id", $id);
    $stmt->bindParam("publish", $publish);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}