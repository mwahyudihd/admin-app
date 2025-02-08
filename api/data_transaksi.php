<?php
include '../models/m_transaksi.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

function uploadFoto($file, $path) {
    $targetDir = $path;
    $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
    $randomFileName = uniqid() . '.' . $fileExt;
    $targetFilePath = $targetDir . $randomFileName;
    $targetFilePathData = '../' . $targetFilePath;
    if (!move_uploaded_file($file["tmp_name"], $targetFilePathData)) {
        throw new Exception("Failed to move uploaded file.");
    }
    return $targetFilePath;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'insert') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (!$inputData) {
        http_response_code(400);
        echo json_encode([
            "statusCode" => 400, 
            "message" => "Invalid JSON data"
        ]);
        exit;
    }

    $result = insertTransaksi($inputData);
    http_response_code($result["statusCode"]);
    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getAll') {
    $limit = (isset($_GET['entries'])) ? (int)$_GET['entries'] : 10;
    $offset = (isset($_GET['page'])) ? (int)$_GET['page'] * 10 : 0;

    $result = getAllTransaksi($offset, $limit);

    if ($result) {
        http_response_code(200);
        echo json_encode([
            "statusCode" => 200,
            "message" => "Data transaksi berhasil diambil!",
            "data" => $result
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "statusCode" => 404,
            "message" => "Data transaksi tidak ditemukan!"
        ]);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $id = (isset($_GET['id'])) ? $_GET['id'] : null;

    $data = json_decode(file_get_contents('php://input'), true);

    
    file_put_contents('log.txt', json_encode($_FILES), FILE_APPEND);
    if (isset($_FILES['bukti']) && !empty($_FILES['bukti'])) {
        file_put_contents('log.txt', json_encode($_POST), FILE_APPEND);
        try {
            $buktiPath = uploadFoto($_FILES['bukti'], "assets/images/transactions/");
            $statusTr = (isset($_POST['status'])) ? $_POST['status'] : $data['status'];
            $result = setConfirmation($id, $buktiPath, $statusTr);
            http_response_code($result['statusCode']);
            echo json_encode($result);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "statusCode" => 500,
                "message" => "Failed to upload file: " . $e->getMessage()
            ]);
            exit;
        }
    }else {
        if ($id === null || empty($data)) {
            http_response_code(400);
            echo json_encode([
                "statusCode" => 400,
                "message" => "ID is required."
            ]);
            exit;
        }
    
        $result = updateTransaksi($id, $data);
    
        http_response_code($result['statusCode']);
        echo json_encode($result);
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'updateByCode' && $_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['kode'])) {
    $kode = (isset($_GET['kode'])) ? $_GET['kode'] : null;

    $data = json_decode(file_get_contents('php://input'), true);

    file_put_contents('log.txt', json_encode($_FILES), FILE_APPEND);

    if (isset($_FILES['image'])) {
        try {
            $buktiPath = uploadFoto($_FILES['image'], "assets/images/transactions/");
            // Merge new data with existing data
            $data = array_merge($data, ['bukti' => $buktiPath]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "statusCode" => 500,
                "message" => "Failed to upload file: " . $e->getMessage()
            ]);
            exit;
        }
    }

    if ($kode === null || empty($data)) {
        http_response_code(400);
        echo json_encode([
            "statusCode" => 400,
            "message" => "ID is required."
        ]);
        exit;
    }

    $result = updateTransaksiByOrderCode($kode, $data);

    http_response_code($result['statusCode']);
    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    try{
        $result = deleteOneTr($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "Transaction deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "Error deleting transaction"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error :".$err]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['q'])) {
    $word = $_GET['q'];

    $result = searchTransaction($word);

    if (count($result) > 0) {
        http_response_code(200);
        echo json_encode([
            "statusCode" => 200,
            "message" => "Transaction is found!.",
            "data" => $result
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "statusCode" => 404,
            "message" => "Transaction is not found!."
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getOne' && isset($_GET['id'])) {
    $setId = $_GET['id'];

    if (!$setId) {
        http_response_code(400);
        echo json_encode(["statusCode" => 400, "message" => "ID is required"]);
    }

    $response = findOneTransaction($setId);
    http_response_code($response["statusCode"]);
    echo json_encode($response);
}

if (isset($_GET['category']) && $_GET['category'] == 'currently' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = getRecentTransactions();
    
    http_response_code($response["statusCode"]);
    echo json_encode($response);
}

if (isset($_GET['category']) && $_GET['category'] == 'totally' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = getTotalHarga();
    
    http_response_code($response["statusCode"]);
    echo json_encode($response);
}

if (isset($_GET['category']) && $_GET['category'] == 'successTransaction' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = getSuccessTransactions();
    
    http_response_code($response["statusCode"]);
    echo json_encode($response);
}

if (isset($_GET['category']) && $_GET['category'] == 'fail' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = getFailTransactions();
    
    http_response_code($response["statusCode"]);
    echo json_encode($response);
}