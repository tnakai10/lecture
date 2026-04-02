<?php

/**
 * @file
 * ステップ 7: Twig テンプレートを使ったエントリポイント.
 *
 * step5 の PHP テンプレートを Twig テンプレートに置き換えた例。
 */

require_once dirname(__DIR__, 2) . '/../vendor/autoload.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/NodeController.php';

$db = new Database();
$router = new Router($db);
$router->route();
