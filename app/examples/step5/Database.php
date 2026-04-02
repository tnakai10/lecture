<?php

/**
 * @file
 * データベース操作を担当するクラス.
 */

class Database {

  private PDO $pdo;

  public function __construct() {
    $this->pdo = new PDO('mysql:host=db;dbname=db', 'db', 'db');
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * ノード一覧を取得する.
   */
  public function getNodes(): array {
    return $this->pdo
      ->query("SELECT nid, title, type FROM node_field_data ORDER BY nid")
      ->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * ノードを削除する（プリペアドステートメント使用）.
   */
  public function deleteNode(int $nid): void {
    $stmt = $this->pdo->prepare('DELETE FROM node WHERE nid = ?');
    $stmt->execute([$nid]);
    $stmt = $this->pdo->prepare('DELETE FROM node_field_data WHERE nid = ?');
    $stmt->execute([$nid]);
  }

}
