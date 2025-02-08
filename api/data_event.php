<?php
include '../models/m_event.php';

// Set CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Get all event
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'getAll') {
    try {
        if (isset($_GET['noLimit']) && $_GET['noLimit'] == 'true' && isset($_GET['status']) && $_GET['status'] == 'publish') {
            $events = findAllEvents();
        } else {
            $limit = (isset($_GET['entries'])) ? (int)$_GET['entries'] : 10;
            $offset = (isset($_GET['page'])) ? (int)$_GET['page'] * $limit : 0;
            $events = getAllEvent($offset, $limit);
        }
    
        http_response_code(200);
        echo json_encode($events);
    } catch (Exception $err) {
        http_response_code(500);
        echo json_encode([ "statusCode" => 500, "message" => "Internal Server Error!", "error" => $err ]);
    }
}

// Insert event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'insert') {
    $data = json_decode(file_get_contents('php://input'), true);
    try{
        if (isset($data['judul'], $data['ukm'], $data['notelp'], $data['tanggal_acara'], $data['waktu_acara'], $data['lokasi_acara'], $data['harga_tiket'], $data['deskripsi'], $data['publish'], $data['foto'])) {
            $id = insertEvent($data['judul'], $data['ukm'], $data['notelp'], $data['tanggal_acara'], $data['waktu_acara'], $data['lokasi_acara'], $data['harga_tiket'], $data['deskripsi'], $data['publish'], $data['foto']);
            http_response_code(200);
            echo json_encode(["message" => "event inserted successfully", "id" => $id]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500, "message" => "Internal Server Error", "error" => $err]);
    }
}

// Find one event by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'findOne') {
    try {
        $id = $_GET['id'];
        $data = findOneEvent($id);
        if ($data) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "Event is Found","data" => $data]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "Event isn't Found"]);
        }
    } catch (\Throwable $err) {
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error","error" => $err]);
    }
}

// Delete event
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    try{
        $result = deleteOneEvent($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "event deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "Error deleting event"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error :".$err]);
    }
}

// Search event
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q']) && isset($_GET['action']) && $_GET['action'] == 'search') {
    $query = $_GET['q'];
    try {
        $data = searchEvent($query);
        if ($data) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200, "message" => "Event is Found!", "data" => $data]);
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
