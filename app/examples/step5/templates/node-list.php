<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 5: OOP で構造化</title>
  <style>
    body { font-family: sans-serif; margin: 2em; }
    table { border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
    th { background: #f5f5f5; }
    .delete-link { color: red; }
  </style>
</head>
<body>
  <h1>Step 5: OOP で構造化したノード管理ページ</h1>

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
  <?php endif; ?>

  <h2>step4 との違い</h2>
  <ul>
    <li>データベース操作 → <code>Database.php</code></li>
    <li>ルーティング → <code>Router.php</code></li>
    <li>ビジネスロジック → <code>NodeController.php</code></li>
    <li>HTML テンプレート → <code>templates/node-list.php</code></li>
    <li>SQL インジェクション対策済み（プリペアドステートメント使用）</li>
  </ul>
</body>
</html>
