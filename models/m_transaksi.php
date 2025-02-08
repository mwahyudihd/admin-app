<?php
require 'database.php';
require '../controllers/lib/str_style.php';

function insertTransaksi($data) {
    global $pdo;

    $kode = (isset($data['kode'])) ? strtoupper($data['kode']) : null;
    $nama_customer = (isset($data['nama_customer'])) ? $data['nama_customer'] : null;
    $produk = (isset($data['produk'])) ? $data['produk'] : null;
    $qty = (isset($data['qty'])) ? $data['qty'] : 0;
    $harga = (isset($data['harga'])) ? $data['harga'] : 0;
    $metode = (isset($data['metode'])) ? $data['metode'] : null;
    $status = (isset($data['status'])) ? toSnakeCase($data['status']) : null;
    $deadline = (isset($data['deadline'])) ? $data['deadline'] : null;
    $email = (isset($data['email'])) ? $data['email'] : '';

    $total_harga = $harga * $qty;

    $sql = "INSERT INTO transaksi (
                kode, nama_customer, produk, qty, harga, total_harga, metode, status, deadline, bukti_pembayaran, created_at, email
            ) VALUES (
                :kode, :nama_customer, :produk, :qty, :harga, :total_harga, :metode, :status, :deadline, NULL, CURRENT_TIMESTAMP, :email
            )";

    $stmt = $pdo->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':kode', $kode);
    $stmt->bindParam(':nama_customer', $nama_customer);
    $stmt->bindParam(':produk', $produk);
    $stmt->bindParam(':qty', $qty);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':total_harga', $total_harga);
    $stmt->bindParam(':metode', $metode);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':deadline', $deadline);
    $stmt->bindParam(':email', $email);

    try {
        $stmt->execute();
        return [
            "statusCode" => 201, 
            "message" => "Transaksi berhasil ditambahkan!"
        ];
    } catch (PDOException $e) {
        return [
            "statusCode" => 500, 
            "message" => "Error: " . $e->getMessage()
        ];
    }
}

function getAllTransaksi($offset, $limit) {
    global $pdo;

    // Query SQL untuk mengambil data transaksi dengan LIMIT dan OFFSET
    $sql = "SELECT * FROM transaksi LIMIT :limit OFFSET :offset";

    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Bind parameter
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

    // Eksekusi query
    $stmt->execute();

    // Mengembalikan semua transaksi dalam bentuk array asosiatif
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

// Fungsi untuk memperbarui data transaksi
function updateTransaksi($id, $data) {
    global $pdo;

    // Menyiapkan bagian query untuk kolom yang ingin diupdate
    $setQuery = [];
    $params = [':id' => $id]; // Inisialisasi parameter dengan id

    // Menambahkan kolom yang diupdate ke query dan parameter
    if (isset($data['kode'])) {
        $setQuery[] = 'kode = :kode';
        $params[':kode'] = $data['kode'];
    }
    if (isset($data['nama_customer'])) {
        $setQuery[] = 'nama_customer = :nama_customer';
        $params[':nama_customer'] = $data['nama_customer'];
    }
    if (isset($data['produk'])) {
        $setQuery[] = 'produk = :produk';
        $params[':produk'] = $data['produk'];
    }
    if (isset($data['qty'])) {
        $setQuery[] = 'qty = :qty';
        $params[':qty'] = $data['qty'];
    }
    if (isset($data['harga'])) {
        $setQuery[] = 'harga = :harga';
        $params[':harga'] = $data['harga'];
    }
    if (isset($data['metode'])) {
        $setQuery[] = 'metode = :metode';
        $params[':metode'] = $data['metode'];
    }
    if (isset($data['status'])) {
        $setQuery[] = 'status = :status';
        $params[':status'] = toSnakeCase($data['status']);
    }

    if (isset($data['deadline'])) {
        $setQuery[] = 'deadline = :deadline';
        $params[':deadline'] = $data['deadline'];
    }

    // Jika ada perubahan harga atau qty, hitung total_harga
    if (isset($data['harga']) || isset($data['qty'])) {
        // Ambil harga dan qty dari database jika salah satu berubah
        $sql = "SELECT harga, qty FROM transaksi WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaksi) {
            return [
                "statusCode" => 404,
                "message" => "Transaksi tidak ditemukan"
            ];
        }

        // Tentukan harga dan qty yang digunakan untuk total_harga
        $harga = isset($data['harga']) ? $data['harga'] : $transaksi['harga'];
        $qty = isset($data['qty']) ? $data['qty'] : $transaksi['qty'];
        
        // Hitung total_harga berdasarkan harga dan qty yang baru
        $total_harga = $harga * $qty;

        // Tambahkan total_harga ke query update
        $setQuery[] = 'total_harga = :total_harga';
        $params[':total_harga'] = $total_harga;
    }

    if (isset($data['bukti_pembayaran'])) {
        $setQuery[] = 'bukti_pembayaran = :bukti_pembayaran';
        $params[':bukti_pembayaran'] = $data['bukti_pembayaran'];
    }

    // Jika tidak ada data yang diubah, kembalikan error
    if (empty($setQuery)) {
        return [
            "statusCode" => 400,
            "message" => "Tidak ada data yang diubah."
        ];
    }

    // Gabungkan query untuk kolom yang diupdate
    $sql = "UPDATE transaksi SET " . implode(', ', $setQuery) . " WHERE id = :id";

    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Eksekusi query
    try {
        $stmt->execute($params);
        return [
            "statusCode" => 200,
            "message" => "Transaksi berhasil diperbarui!"
        ];
    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}

function updateTransaksiByOrderCode($kode, $data) {
    global $pdo;

    // Menyiapkan bagian query untuk kolom yang ingin diupdate
    $setQuery = [];
    $params = [':kode' => $kode]; // Inisialisasi parameter dengan kode


    if (isset($data['nama_customer'])) {
        $setQuery[] = 'nama_customer = :nama_customer';
        $params[':nama_customer'] = $data['nama_customer'];
    }
    if (isset($data['produk'])) {
        $setQuery[] = 'produk = :produk';
        $params[':produk'] = $data['produk'];
    }
    if (isset($data['qty'])) {
        $setQuery[] = 'qty = :qty';
        $params[':qty'] = $data['qty'];
    }
    if (isset($data['harga'])) {
        $setQuery[] = 'harga = :harga';
        $params[':harga'] = $data['harga'];
    }
    if (isset($data['metode'])) {
        $setQuery[] = 'metode = :metode';
        $params[':metode'] = $data['metode'];
    }
    if (isset($data['status'])) {
        $setQuery[] = 'status = :status';
        $params[':status'] = toSnakeCase($data['status']);
    }

    if (isset($data['deadline'])) {
        $setQuery[] = 'deadline = :deadline';
        $params[':deadline'] = $data['deadline'];
    }

    // Jika ada perubahan harga atau qty, hitung total_harga
    if (isset($data['harga']) || isset($data['qty'])) {
        // Ambil harga dan qty dari database jika salah satu berubah
        $sql = "SELECT harga, qty FROM transaksi WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaksi) {
            return [
                "statusCode" => 404,
                "message" => "Transaksi tidak ditemukan"
            ];
        }

        // Tentukan harga dan qty yang digunakan untuk total_harga
        $harga = isset($data['harga']) ? $data['harga'] : $transaksi['harga'];
        $qty = isset($data['qty']) ? $data['qty'] : $transaksi['qty'];
        
        // Hitung total_harga berdasarkan harga dan qty yang baru
        $total_harga = $harga * $qty;

        // Tambahkan total_harga ke query update
        $setQuery[] = 'total_harga = :total_harga';
        $params[':total_harga'] = $total_harga;
    }

    if (isset($data['bukti'])) {
        $setQuery[] = 'bukti_pembayaran = :bukti_pembayaran';
        $params[':bukti_pembayaran'] = $data['bukti'];
    }

    // Jika tidak ada data yang diubah, kembalikan error
    if (empty($setQuery)) {
        return [
            "statusCode" => 400,
            "message" => "Tidak ada data yang diubah."
        ];
    }

    // Gabungkan query untuk kolom yang diupdate
    $sql = "UPDATE transaksi SET " . implode(', ', $setQuery) . " WHERE kode = :kode";

    // Menyiapkan statement
    $stmt = $pdo->prepare($sql);

    // Eksekusi query
    try {
        $stmt->execute($params);
        return [
            "statusCode" => 200,
            "message" => "Transaksi berhasil diperbarui!"
        ];
    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}

function deleteOneTr($id) {
    global $pdo;
    $sql = "DELETE FROM transaksi WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function searchTransaction($word) {
    global $pdo;

    $sql = "SELECT * FROM transaksi WHERE produk LIKE :word OR nama_customer LIKE :word";
    $stmt = $pdo->prepare($sql);

    $likeWord = "%" . $word . "%";
    $stmt->bindParam(':word', $likeWord, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function findOneTransaction($id) {
    global $pdo;
    
    // Inisialisasi response
    $response = [
        'statusCode' => 400,
        'message' => 'Data not found or error occurred',
        'data' => null
    ];

    try {
        // Query untuk mencari transaksi berdasarkan id
        $sql = "SELECT * FROM transaksi WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Ambil hasil
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Cek apakah data ditemukan
        if ($result) {
            $response['statusCode'] = 200;
            $response['message'] = 'Data found successfully';
            $response['data'] = $result;
        } else {
            $response['statusCode'] = 404;
            $response['message'] = 'Data not found';
        }
    } catch (PDOException $e) {
        // Tangani error dari database
        $response['statusCode'] = 500;
        $response['message'] = 'Internal Server Error: ' . $e->getMessage();
    } catch (Exception $e) {
        // Tangani error umum
        $response['statusCode'] = 500;
        $response['message'] = 'Unexpected Error: ' . $e->getMessage();
    }

    return $response;
}

function getRecentTransactions() {
    global $pdo;
    $fiveDaysAgo = date('Y-m-d H:i:s', strtotime('-5 days'));
    $sql = "SELECT * FROM transaksi WHERE created_at >= :fiveDaysAgo AND (status = 'Completed' OR status = 'Selesai' OR status = 'LUNAS' OR status = 'BERHASIL' OR status = 'Berhasil') ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':fiveDaysAgo', $fiveDaysAgo, PDO::PARAM_STR);

    try {
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                "statusCode" => 200,
                "message" => "Successfully obtain transaction data.",
                "data" => $result
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "No transactions in the last 5 days."
            ];
        }

    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Internal Sever Error: " . $e->getMessage()
        ];
    }
}

function getTotalHarga() {
    global $pdo;

    try {
        $sql = "SELECT SUM(total_harga) AS total_sum FROM transaksi WHERE status = 'completed' OR status = 'selesai' OR status = 'lunas' OR status = 'berhasil' OR status = 'complete'";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return [
                "statusCode" => 200,
                "message" => "Total price calculated successfully.",
                "total_harga" => $result['total_sum']
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "Data transaction not found."
            ];
        }
        
    } catch (PDOException $e) {
        // Jika terjadi error
        return [
            "statusCode" => 500,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}

function getSuccessTransactions() {
    global $pdo;

    $sql = "SELECT total_harga FROM transaksi WHERE status = 'completed' OR status = 'selesai' OR status = 'lunas' OR status = 'berhasil' ORDER BY created_at ASC";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                "statusCode" => 200,
                "message" => "Successfully obtain transaction data.",
                "data" => $result
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "No Successful Transactions."
            ];
        }

    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Internal Server Error: " . $e->getMessage()
        ];
    }
}

function getFailTransactions() {
    global $pdo;

    $sql = "SELECT total_harga FROM transaksi WHERE status = 'Failed' OR status = 'Gagal' OR status = 'Batal' OR status = 'Cenceled' OR status = 'GAGAL' OR status = 'Tidak Sah' ORDER BY created_at ASC";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                "statusCode" => 200,
                "message" => "Successfully obtain transaction data.",
                "data" => $result
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "No Successful Transactions."
            ];
        }

    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Internal Server Error: " . $e->getMessage()
        ];
    }
}

function updateTransactionStatus($order_id, $status) {
    global $pdo;

    $sql = "UPDATE transaksi SET status = :status WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $order_id);
    $stmt->bindParam(':status', $status);

    try {
        $stmt->execute();
        return [
            "statusCode" => 200,
            "message" => "Status transaksi berhasil diperbarui"
        ];
    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}

function getHistori($email, $condition, $status) {
    global $pdo;
    $stmt = null;  // Initialize $stmt

    if (isset($condition) && $condition == 'getActive') {
        $setQuery = "SELECT * FROM transaksi WHERE email = :email AND deadline >= NOW() AND status = 'aktif' OR 
            email = :email AND deadline >= NOW() AND status = 'berhasil' OR
            email = :email AND deadline >= NOW() AND status = 'selesai' OR 
            email = :email AND deadline >= NOW() AND status = 'completed' OR 
            email = :email AND deadline >= NOW() AND status = 'complete'";
        $stmt = $pdo->prepare($setQuery);
        $stmt->bindParam(':email', $email);
    }
    elseif (isset($condition) && $condition == 'allHistory') {
        $setQuery = "SELECT * FROM transaksi WHERE email = :email";
        $stmt = $pdo->prepare($setQuery);
        $stmt->bindParam(':email', $email);
    }
    elseif (isset($condition) && $condition == 'withStatus') {
        $setQuery = "SELECT * FROM transaksi WHERE email = :email AND deadline >= NOW() AND status = :status";
        $stmt = $pdo->prepare($setQuery);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':email', $email);
    }
    else {
        return [
            "statusCode" => 400,
            "message" => "Invalid condition provided."
        ];
    }

    try {
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                "statusCode" => 200,
                "message" => "Successfully obtained transaction data.",
                "data" => $result
            ];
        } else {
            return [
                "statusCode" => 404,
                "message" => "No history data transactions."
            ];
        }

    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Internal Server Error: " . $e->getMessage()
        ];
    }
}

function setConfirmation($id, $bukti, $status){
    global $pdo;

    $sql = "UPDATE transaksi SET status = :status, bukti_pembayaran = :bukti WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':bukti', $bukti);

    try {
        $stmt->execute();
        return [
            "statusCode" => 200,
            "message" => "Status transaksi berhasil diperbarui"
        ];
    } catch (PDOException $e) {
        return [
            "statusCode" => 500,
            "message" => "Error: " . $e->getMessage()
        ];
    }
}
