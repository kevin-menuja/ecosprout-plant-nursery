<?php
require_once __DIR__ . '/../includes/functions.php';
session_destroy();
header('Location: ' . app_url('index.php'));
exit;
