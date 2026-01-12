<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Pc\WallitSystem\Core\Session;

Session::start();

Session::remove('user_id');
Session::remove('user_name');

Session::destroy();
header('Location: /wallit_system/pages/login.php');
exit;
