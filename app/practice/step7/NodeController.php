<?php

/**
 * @file
 * ノードに関する処理を担当するコントローラ.
 *
 * step5 との違い: PHP テンプレートの代わりに Twig テンプレートを使用。
 */

class NodeController {

  private Database $db;
  private Twig\Environment $twig;

  public function __construct(Database $db) {
    $this->db = $db;

    // Twig の初期化.
    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $this->twig = new Twig\Environment($loader, [
      'cache' => __DIR__ . '/cache',
      'auto_reload' => TRUE,
    ]);
  }

  /**
   * ノード一覧を表示する.
   */
  public function index(): void {
    $nodes = $this->db->getNodes();
    echo $this->twig->render('node-list.html.twig', [
      'nodes' => $nodes,
    ]);
  }

  /**
   * ノードを削除する.
   */
  public function delete(int $nid): void {
    $this->db->deleteNode($nid);
  }

}
