<?php

/**
 * @file
 * ノードに関する処理を担当するコントローラ.
 */

class NodeController {

  private Database $db;

  public function __construct(Database $db) {
    $this->db = $db;
  }

  /**
   * ノード一覧を表示する.
   */
  public function index(): void {
    $nodes = $this->db->getNodes();
    include __DIR__ . '/templates/node-list.php';
  }

  /**
   * ノードを削除する.
   */
  public function delete(int $nid): void {
    $this->db->deleteNode($nid);
  }

}
