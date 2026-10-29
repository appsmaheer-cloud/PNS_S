<?php
require_once dirname(__DIR__) . '/config/config.php';

abstract class Controller {
    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        $fullPath = dirname(__DIR__) . '/views/' . $viewPath . '.php';
        if (file_exists($fullPath)) {
            require_once $fullPath;
        } else {
            echo "View [{$viewPath}] not found at {$fullPath}";
        }
    }

    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
