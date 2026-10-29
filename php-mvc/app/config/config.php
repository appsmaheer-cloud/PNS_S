<?php
define('APP_NAME', 'EHMS - Enterprise Health Management System');
define('APP_VERSION', '2.5.0-Enterprise');
define('COMPANY_NAME', 'PT PGAS Solution');

// Tentukan Base URL secara otomatis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/mcu/index.php';
$baseDir = dirname($scriptName);
$baseDir = str_replace('\\', '/', $baseDir);
$baseDir = rtrim($baseDir, '/');

define('BASE_URL', $protocol . $host . $baseDir);
define('DB_PATH', dirname(__DIR__, 2) . '/database/ehms.sqlite');
define('GAS_WEBAPP_URL', 'https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec');
