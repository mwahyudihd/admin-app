<?php
$base_url = 'http://localhost/admin-ulbi';

function baseUrl(){
    global $base_url;
    return $base_url;
}

function strBaseUrl(){
    global $base_url;
    echo $base_url;
}

function redirectToError(){
    global $base_url;
    header('Location: ' . $base_url . '/error');
    exit;
}

function redirectTo($str){
    global $base_url;
    header('Location: ' . $base_url . $str);
    exit;
}

function redirectToWith($str, $data){
    global $base_url;
    ob_start();
    echo "<script>sessionStorage.setItem('status', '" . addslashes($data) . "');</script>";
    header('Location: ' . $base_url . $str);
    exit;
    ob_end_flush();
}
