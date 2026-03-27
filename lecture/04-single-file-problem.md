# ステップ 4: 1 つのファイルに何でも書けてしまう問題

## やること

データベース接続、ロジック、HTML 出力をすべて 1 ファイルに詰め込んだ例を見せる。（実行しなくてもよい。コードを見せて問題点を説明する）

```php
<?php
// データベースに接続
$db = new PDO('mysql:host=localhost;dbname=mysite', 'user', 'password');

// URL のパラメータを取得
$page = $_GET['page'] ?? 'home';

// ページ内容をデータベースから取得
$stmt = $db->prepare('SELECT title, body FROM pages WHERE slug = ?');
$stmt->execute([$page]);
$row = $stmt->fetch();

// ユーザーがログインしているか判定
session_start();
$is_logged_in = isset($_SESSION['user']);

// フォームの送信を処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $db->prepare('INSERT INTO comments (page, body) VALUES (?, ?)')->execute([$page, $comment]);
}

// コメント一覧を取得
$comments = $db->query("SELECT * FROM comments WHERE page = '$page'")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $row['title']; ?></title>
</head>
<body>
  <h1><?php echo $row['title']; ?></h1>
  <div><?php echo $row['body']; ?></div>

  <h2>コメント</h2>
  <?php foreach ($comments as $c): ?>
    <p><?php echo $c['body']; ?></p>
  <?php endforeach; ?>

  <?php if ($is_logged_in): ?>
    <form method="post">
      <textarea name="comment"></textarea>
      <button type="submit">投稿</button>
    </form>
  <?php endif; ?>
</body>
</html>
```

## 何が問題か

- データベース接続、データ取得、ログイン判定、フォーム処理、HTML 出力がすべて 1 ファイルに混在
- ページが増えるたびに同じようなコードをコピペすることになる
- セキュリティ対策の漏れが起きやすい（上のコードにも SQL インジェクションの脆弱性がある）
- デザインを変えたいだけなのに PHP コードを触る必要がある
- 複数人で開発すると、変更が衝突する

実際に初期の PHP の Web サイトはこのように書かれていたが、サイトが大きくなると管理が破綻していった。
