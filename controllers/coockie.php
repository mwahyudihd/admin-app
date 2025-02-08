<?php
require 'models/m_user.php';

if (isset($_COOKIE['remember_token'])) {
    $rememberToken = $_COOKIE['remember_token'];
    loginWithRememberToken($rememberToken);
}