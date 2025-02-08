<?php
include '../models/m_berita.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


// Get all berita
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getAll') {
    try {
        if (isset($_GET['noLimit']) && $_GET['noLimit'] == 'true') {
            $news = findAllNews();
        } else {
            $limit = (isset($_GET['entries'])) ? (int)$_GET['entries'] : 10;
            $offset = (isset($_GET['page'])) ? (int)$_GET['page'] * $limit : 0;
            $news = getAllNews($offset, $limit);
        }
    
        http_response_code(200);
        echo json_encode($news);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([ "statusCode" => 500, "message" => "Internal Server Error!", "error" => $err ]);
    }
}

// Insert berita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'insert') {
    $data = json_decode(file_get_contents('php://input'), true);
    try{
        if (isset($data['judul_berita'], $data['creator'], $data['notelp'], $data['deskripsi'], $data['foto'], $data['publish'])) {
            $id = insertNews($data['judul_berita'], $data['creator'], $data['notelp'], $data['deskripsi'], $data['foto'], $data['publish']);
            http_response_code(200);
            echo json_encode(["message" => "Berita inserted successfully", "id" => $id]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error", "error" => $err]);
    }
}

// Find one berita by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && $_GET['action'] == 'findOne') {
    try {
        $id = $_GET['id'];
        $data = findOneNews($id);
        if ($data) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "News is Found","data" => $data]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "News isn't Found"]);
        }
    } catch (\Throwable $err) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error","error" => $err]);
    }
}

// Delete berita
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    try{
        $result = deleteOneNews($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "Berita deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "Error deleting berita"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error :".$err]);
    }
}

// Search berita
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q']) && $_GET['action'] == 'search') {
    $query = $_GET['q'];
    try {
        $data = searchNews($query);
        if ($data) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200, "message" => "News is Found!", "data" => $data]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404, "message" => "Data is Not found"]);
        }
        
    } catch (\Throwable $err) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error :".$err]);
    }
}

// API endpoint untuk update status publish
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id']) && $_GET['action'] == 'publish') {
    $body = json_decode(file_get_contents('php://input'), true);

    if (isset($body['publish'])) {
        $id = $_GET['id'];
        $publish = $body['publish'];

        if ($publish !== 1 && $publish !== 0) {
            echo json_encode(["statusCode" => 400, "message" => "Invalid publish value. Use 1 for true or 0 for false."]);
            exit;
        }

        try {
            $result = updatePublish($id, $publish);
            if ($result) {
                http_response_code(200);
                echo json_encode(["statusCode" => 200, "message" => ($publish == 1) ? "Berhasih Dipublish!" : "Publish status updated successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["statusCode" => 404, "message" => "Error updating publish status"]);
            }
        } catch (\Exception $err) {
            http_response_code(500);
            echo json_encode(["statusCode" => 500, "message" => "Internal Server Error: ".$err]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["statusCode" => 400, "message" => "Publish value is required"]);
    }
}
