<?php
require 'lib/base.php';
require 'coockie.php';
session_start();

if (empty($_SESSION['role']) || $_SESSION['role'] != 'admin' && $_SESSION['role'] != 'Admin') {
    logout();
    session_destroy();
    redirectTo('/login.php');
}