<?php

/**
 * @file
 * ステップ 6: Twig テンプレートのコンパイル結果を確認するデモ.
 *
 * Drupal の Twig テンプレートが PHP にコンパイルされることを確認する。
 */

// Twig キャッシュディレクトリ.
$twigCacheDir = __DIR__ . '/../../sites/default/files/php/twig';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 6: Twig テンプレート</title>
  <style>
    body { font-family: sans-serif; margin: 2em; }
    table { border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
    th { background: #f5f5f5; }
    td { vertical-align: top; }
    pre { background: #f5f5f5; padding: 1em; overflow-x: auto; font-size: 0.9em; }
    .path { font-size: 0.85em; color: #666; word-break: break-all; }
    .section { margin-top: 2em; }
  </style>
</head>
<body>
  <h1>Step 6: Twig テンプレートの仕組み</h1>

  <div class="section">
    <h2>1. Twig テンプレートの例</h2>
    <p>Drupal のテンプレートは <code>.html.twig</code> という拡張子を持つ。</p>
    <pre>&lt;div{{ attributes }}&gt;
  {{ title_prefix }}
  {% if label %}
    &lt;h2{{ title_attributes }}&gt;{{ label }}&lt;/h2&gt;
  {% endif %}
  {{ title_suffix }}

  &lt;div{{ content_attributes }}&gt;
    {{ content }}
  &lt;/div&gt;
&lt;/div&gt;</pre>
    <ul>
      <li><code>{{ ... }}</code> : 変数の出力（自動エスケープ）</li>
      <li><code>{% ... %}</code> : 制御構文（if、for など）</li>
      <li><code>{# ... #}</code> : コメント（出力されない）</li>
    </ul>
  </div>

  <div class="section">
    <h2>2. コンパイル済み PHP ファイル</h2>
    <p>Twig テンプレートは PHP にコンパイルされ、<code>sites/default/files/php/twig/</code> にキャッシュされる。</p>

    <?php if (!is_dir($twigCacheDir)): ?>
      <p>Twig キャッシュディレクトリが見つかりません。Drupal のページに一度アクセスしてキャッシュを生成してください。</p>
    <?php else: ?>
      <?php
      // コンパイル済みファイルを最大 5 件取得.
      $files = glob($twigCacheDir . '/*/*.php');
      $files = array_slice($files, 0, 5);
      ?>

      <?php if (empty($files)): ?>
        <p>コンパイル済みファイルがありません。Drupal のページに一度アクセスしてください。</p>
      <?php else: ?>
        <p><?php echo count(glob($twigCacheDir . '/*/*.php')); ?> 件のコンパイル済みファイルが見つかりました（先頭 5 件を表示）:</p>
        <table>
          <tr>
            <th>#</th>
            <th>ファイル名</th>
            <th>サイズ</th>
          </tr>
          <?php foreach ($files as $i => $file): ?>
            <tr>
              <td><?php echo $i + 1; ?></td>
              <td class="path"><?php echo htmlspecialchars(basename($file)); ?></td>
              <td><?php echo number_format(filesize($file)); ?> bytes</td>
            </tr>
          <?php endforeach; ?>
        </table>

        <h3>コンパイル後の PHP（先頭 30 行）</h3>
        <p class="path"><?php echo htmlspecialchars(basename($files[0])); ?></p>
        <pre><?php
        $lines = file($files[0]);
        $lines = array_slice($lines, 0, 30);
        echo htmlspecialchars(implode('', $lines));
        ?></pre>
        <p>Twig の構文が PHP の <code>yield</code> 文に変換されていることがわかる。</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <div class="section">
    <h2>3. まとめ: Twig の処理フロー</h2>
    <pre>
.html.twig ファイル
     ↓
[コンパイル] Twig エンジンが PHP コードに変換
     ↓
.php ファイル（キャッシュとして保存）
     ↓
[実行] PHP エンジンが実行
     ↓
HTML 文字列（これがブラウザに返る）</pre>
  </div>

</body>
</html>
