<?php
require '../models/m_payment.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try{
        $result = delPaymentMethod($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(["statusCode" => 200,"message" => "Payment method deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(["statusCode" => 404,"message" => "Error deleting method"]);
        }
    }catch(\Exception $err){
        http_response_code(500);
        echo json_encode(["statusCode" => 500,"message" => "Internal Server Error :".$err]);
    }
}else{
    http_response_code(404);
    echo json_encode(["statusCode" => 404,"message" => "Error deleting payment method"]);
}