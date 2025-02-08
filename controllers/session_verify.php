<?php
session_start();
require 'lib/base.php';
require 'models/m_user.php';

if (isset($_COOKIE['remember_token'])) {
    $rememberToken = $_COOKIE['remember_token'];
    loginWithRememberToken($rememberToken);
}

if (!empty($_SESSION['role'] && $_SESSION['role'] == 'admin' || !empty($_SESSION['role'] && $_SESSION['role'] == 'Admin'))) {
    redirectTo('/dashboard.php');
}