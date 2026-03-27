# ステップ 5: OOP（オブジェクト指向）で構造化する

## 考え方

ステップ 4 の問題を解決するために、役割ごとにコードを分離する。

```
ルーティング:    どの URL にアクセスされたか判定する
コントローラ:    リクエストに応じた処理を行う
モデル/サービス: データの取得・保存を担当する
テンプレート:    HTML の見た目を担当する
```

これを PHP のクラスとして整理すると:

```
app/practice/
  step5-oop/
    index.php          ← エントリポイント（すべてのリクエストを受ける）
    Router.php         ← URL を解析してコントローラを呼ぶ
    PageController.php ← ページ表示のロジック
    Database.php       ← データベース操作
    templates/
      page.php         ← HTML テンプレート
```

```php
// index.php（エントリポイント）
<?php
require_once 'Router.php';
require_once 'PageController.php';
require_once 'Database.php';

$router = new Router();
$router->route($_SERVER['REQUEST_URI']);
```

```php
// PageController.php
<?php
class PageController {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function show(string $slug): void {
        $page = $this->db->getPage($slug);
        $comments = $this->db->getComments($slug);

        // テンプレートに変数を渡して描画
        include 'templates/page.php';
    }
}
```

```php
// templates/page.php（テンプレート）
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($page['title']); ?></title>
</head>
<body>
  <h1><?php echo htmlspecialchars($page['title']); ?></h1>
  <div><?php echo $page['body']; ?></div>
</body>
</html>
```

## ポイント

- 各ファイルが 1 つの役割に集中している
- テンプレート（HTML）とロジック（PHP）が分離されている
- データベース操作が 1 箇所にまとまっている
- デザイナーはテンプレートだけを触ればよい
- この考え方が、Drupal のようなフレームワーク / CMS の基礎になっている
