<?php
require 'controllers/lib/base.php';

session_start();

// Memeriksa apakah user sudah login dengan memeriksa session
if (!isset($_SESSION['role'])) {
    redirectTo('/login.php'); 
} else {
    redirectTo('/dashboard.php');
}