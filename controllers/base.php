<?php
$base_url = 'http://localhost/admin-ulbi';

function baseUrl(){
    global $base_url;
    return $base_url;
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

// Fungsi redirect dengan menggunakan session untuk membawa status
function redirectToWith($str, $data){
    global $base_url;
    
    // Mulai session jika belum dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Menyimpan status ke dalam session
    $_SESSION['status'] = $data;

    // Melakukan redirect
    header('Location: ' . $base_url . $str);
    exit;
}
