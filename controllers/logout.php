<?php
require 'lib/base.php';
require '../models/m_user.php';

logout();
session_destroy();
redirectTo('/login.php');