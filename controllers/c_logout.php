<?php
include('base.php');
session_start();
session_unset(); // Menghapus semua session
session_destroy(); // Menghancurkan session
redirectTo('/login.php'); // Redirect ke halaman login setelah logout