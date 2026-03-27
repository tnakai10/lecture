<?php

/**
 * @file
 * ステップ 4: 1 つのファイルに何でも書けてしまう危険性のデモ.
 *
 * このファイルは教育目的で作成されたものです。
 * 本番環境では絶対にこのようなコードを書いてはいけません。
 */

// DDEV 環境のデータベース接続情報.
$db = new PDO('mysql:host=db;dbname=db', 'db', 'db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 削除処理（SQL インジェクション脆弱性あり）.
if (isset($_GET['delete'])) {
    $nid = $_GET['delete'];
    // 危険: ユーザー入力をそのまま SQL に埋め込んでいる.
    $db->exec("DELETE FROM node WHERE nid = $nid");
    $db->exec("DELETE FROM node_field_data WHERE nid = $nid");
}

// ノード一覧を取得.
$nodes = $db->query("SELECT nid, title, type FROM node_field_data ORDER BY nid")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 4: 管理ページ</title>
  <style>
    body { font-family: sans-serif; margin: 2em; }
    table { border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
    th { background: #f5f5f5; }
    .delete-link { color: red; }
    code { background: #f0f0f0; padding: 2px 6px; }
  </style>
</head>
<body>
  <h1>Step 4: 自前のノード管理ページ</h1>

  <h2>Drupal ノード一覧</h2>

  <?php if (empty($nodes)): ?>
    <p>ノードがありません。</p>
  <?php else: ?>
    <table>
      <tr>
        <th>NID</th>
        <th>タイトル</th>
        <th>タイプ</th>
        <th>操作</th>
      </tr>
      <?php foreach ($nodes as $node): ?>
        <tr>
          <td><?php echo htmlspecialchars($node['nid']); ?></td>
          <td><?php echo htmlspecialchars($node['title']); ?></td>
          <td><?php echo htmlspecialchars($node['type']); ?></td>
          <td><a href="?delete=<?php echo $node['nid']; ?>" class="delete-link" onclick="return confirm('本当に削除しますか？')">削除</a></td>
        </tr>
      <?php endforeach; ?>
    </table>

  <p>上の URL パラメータをブラウザのアドレスバーに追加するだけで、ノードが削除されます。ログインも権限チェックも不要です。</p>
  <?php endif; ?>

</body>
</html>
