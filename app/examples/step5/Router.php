<?php

/**
 * @file
 * URL を解析して適切なコントローラを呼び出すクラス.
 */

class Router {

  private Database $db;

  public function __construct(Database $db) {
    $this->db = $db;
  }

  /**
   * リクエストに応じて処理を振り分ける.
   */
  public function route(): void {
    $controller = new NodeController($this->db);

    if (isset($_GET['delete'])) {
      $controller->delete((int) $_GET['delete']);
    }

    $controller->index();
  }

}
