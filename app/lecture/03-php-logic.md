# ステップ 3: PHP には様々なロジックが書けることを確認する

## やること

PHP のコードを含む `index.php` を作成する。

```
app/examples/
└── step3/
    └── index.php
```

```php
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 3: PHP のロジック</title>
</head>
<body>
  <h1>こんにちは！</h1>
  <p>現在の日時: <?php echo date('Y年m月d日 H:i:s'); ?></p>
  <p>今日は<?php echo date('l'); ?>です。</p>

  <?php if (date('H') < 12): ?>
    <p>午前中です。おはようございます！</p>
  <?php else: ?>
    <p>午後です。こんにちは！</p>
  <?php endif; ?>

  <h2>1 から 5 までのリスト</h2>
  <ul>
    <?php for ($i = 1; $i <= 5; $i++): ?>
      <li>項目 <?php echo $i; ?></li>
    <?php endfor; ?>
  </ul>
</body>
</html>
```

## 確認方法

- <https://drupal-project.ddev.site/examples/step3/> にアクセスする
- 開発者ツール（F12）のネットワークタブでレスポンスを確認し、PHP のコード（`<?php ... ?>`）が消えて実行結果だけが HTML として返っていることを確認する
- リロードすると時刻が変わることも確認する

## ポイント

- `<?php ... ?>` の部分が PHP エンジンによって実行され、結果に置き換わる
- ブラウザには PHP コードは一切届かない。届くのは実行結果の HTML だけ
- アクセスするたびに結果が変わりうる（時刻、条件分岐の結果など）
- これが「動的」と呼ばれる理由
