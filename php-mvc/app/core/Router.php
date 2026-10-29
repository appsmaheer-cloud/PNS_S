<?php
class Router {
    public static function dispatch(): void {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = dirname($scriptName);
        $baseDir = str_replace('\\', '/', $baseDir);

        if ($baseDir !== '/' && str_starts_with($uri, $baseDir)) {
            $uri = substr($uri, strlen($baseDir));
        }

        $uri = strtok($uri, '?');
        $uri = '/' . trim($uri, '/');

        // Parameter fallback ?route=...
        if (isset($_GET['route'])) {
            $uri = '/' . trim($_GET['route'], '/');
        }

        // Routing map
        if ($uri === '/' || $uri === '' || $uri === '/dashboard') {
            require_once dirname(__DIR__) . '/controllers/DashboardController.php';
            (new DashboardController())->index();
        } elseif ($uri === '/mcu') {
            require_once dirname(__DIR__) . '/controllers/McuController.php';
            (new McuController())->index();
        } elseif ($uri === '/dcu') {
            require_once dirname(__DIR__) . '/controllers/DcuController.php';
            (new DcuController())->index();
        } elseif ($uri === '/employee') {
            require_once dirname(__DIR__) . '/controllers/EmployeeController.php';
            (new EmployeeController())->index();
        } elseif ($uri === '/comorbid') {
            require_once dirname(__DIR__) . '/controllers/ComorbidController.php';
            (new ComorbidController())->index();
        } elseif ($uri === '/screening') {
            require_once dirname(__DIR__) . '/controllers/ScreeningController.php';
            (new ScreeningController())->index();
        } elseif ($uri === '/api/emr') {
            require_once dirname(__DIR__) . '/controllers/ApiController.php';
            (new ApiController())->emr();
        } elseif ($uri === '/api/stats') {
            require_once dirname(__DIR__) . '/controllers/ApiController.php';
            (new ApiController())->stats();
        } elseif ($uri === '/api/save') {
            require_once dirname(__DIR__) . '/controllers/ApiController.php';
            (new ApiController())->save();
        } elseif ($uri === '/api/delete') {
            require_once dirname(__DIR__) . '/controllers/ApiController.php';
            (new ApiController())->delete();
        } elseif ($uri === '/export/csv') {
            require_once dirname(__DIR__) . '/controllers/ExportController.php';
            (new ExportController())->csv();
        } else {
            // Default ke dashboard jika tidak cocok
            require_once dirname(__DIR__) . '/controllers/DashboardController.php';
            (new DashboardController())->index();
        }
    }
}
