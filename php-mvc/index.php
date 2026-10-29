<?php
/**
 * EHMS - Enterprise Health Management System
 * Entry Point Router untuk Laragon / Apache / PHP Built-in Server
 */
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Router.php';

Router::dispatch();
