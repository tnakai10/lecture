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
  step5/
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

## 補足: MVC パターン

この役割分担は「MVC（Model-View-Controller）」というデザインパターンに基づいている。

| MVC | 役割 | step5 のファイル |
| --- | --- | --- |
| Model（モデル） | データの取得・保存 | `Database.php` |
| View（ビュー） | HTML の表示 | `templates/node-list.php` |
| Controller（コントローラ） | リクエストを受けて Model と View を繋ぐ | `NodeController.php` |

Router（`Router.php`）は MVC には含まれない。リクエストの URL を見て、どの Controller に処理を渡すかを決める「振り分け役」で、MVC の前段に位置する。

```text
リクエスト → Router → Controller → Model → Controller → View → レスポンス
              ↑          ↑------------ MVC ------------↑
           振り分け
```

処理の流れ:

1. Router がリクエストの URL を見て適切な Controller を選ぶ
2. Controller が Model にデータを要求する
3. Model がデータベースからデータを取得して返す
4. Controller がデータを View に渡す
5. View がデータを HTML として表示する

見た目を変えたければ View だけ、データの保存先を変えたければ Model だけを修正すればよい。Drupal をはじめ、多くの Web フレームワークがこのパターンを採用している。
