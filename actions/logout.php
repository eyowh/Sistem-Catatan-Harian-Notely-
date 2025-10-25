<?php
session_start();
session_destroy();
header('Location: /uts pemograman/login.php');
