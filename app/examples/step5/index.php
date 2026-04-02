<?php

/**
 * @file
 * ステップ 5: エントリポイント.
 *
 * すべてのリクエストをここで受け取り、Router に処理を委譲する。
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/NodeController.php';

$db = new Database();
$router = new Router($db);
$router->route();
