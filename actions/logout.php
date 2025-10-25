<?php
session_start();
session_destroy();
require_once __DIR__ . '/../includes/base.php';
$BASE = app_base();
header('Location: ' . $BASE . '/login.php');
