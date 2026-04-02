# ステップ 4: 1 つのファイルに何でも書けてしまう問題

## やること

「Drupal のノードを管理するページを自前で作った」という想定で、データベース接続、ロジック、HTML 出力をすべて 1 ファイルに詰め込んだ例を確認する。

```
app/examples/
└── step4/
    └── index.php
```

ブラウザで <https://drupal-project.ddev.site/examples/step4/> にアクセスすると、Drupal のノード一覧が表示され、削除ボタンでノードを削除できる。

ノードが存在しない場合は、先に Drupal の管理画面（<https://drupal-project.ddev.site/node/add>）からコンテンツを作成しておくこと。

```php
<?php
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
</head>
<body>
  <h1>Step 4: 自前のノード管理ページ</h1>
  <h2>Drupal ノード一覧</h2>
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
        <td><a href="?delete=<?php echo $node['nid']; ?>" class="delete-link">削除</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
```

## 何が問題か

一見動いているように見えるが、このコードには多くの問題がある。

- データベース接続情報がコードに直書きされている
- 削除処理に認証・権限チェックがない（URL を知っていれば誰でも削除できる）
- SQL インジェクションの脆弱性がある（後述）
- データベース操作、ビジネスロジック、HTML 出力がすべて 1 ファイルに混在
- ページが増えるたびに同じようなコードをコピペすることになる
- デザインを変えたいだけなのに PHP コードを触る必要がある
- 複数人で開発すると、変更が衝突する

実際に初期の PHP の Web サイトはこのように書かれていたが、サイトが大きくなると管理が破綻していった。

## 補足: SQL インジェクションの例

上のコードの削除処理に脆弱性がある。

```php
// 危険なコード
$nid = $_GET['delete'];
$db->exec("DELETE FROM node WHERE nid = $nid");
```

`$nid` は URL のパラメータ（`$_GET['delete']`）から取得した値で、ユーザーが自由に書き換えられる。この値がそのまま SQL 文に埋め込まれている。

通常のアクセス（`https://...examples/step4/?delete=1`）:

```sql
DELETE FROM node WHERE nid = 1
```

攻撃者がこのようにアクセスすると（`https://...examples/step4/?delete=1 OR 1=1`）:

```sql
DELETE FROM node WHERE nid = 1 OR 1=1
```

`1=1` は常に真なので、すべてのノードが削除されてしまう。

安全な書き方（プリペアドステートメント）:

```php
// 安全なコード
$stmt = $db->prepare('DELETE FROM node WHERE nid = ?');
$stmt->execute([$nid]);
```

`?` をプレースホルダとして使い、値を別途渡す。こうすると、値の中に SQL の構文が含まれていても、単なる文字列として扱われる。

1 ファイルに処理を詰め込むと、こうしたセキュリティ上のミスに気づきにくい。

## Drupal が正規の削除で行っていること

step4 では `DELETE FROM node WHERE nid = $nid` の 1 行で済ませているが、Drupal が正規の手順でノードを削除する場合、以下のような処理が行われる。

セキュリティ:

| 段階 | 処理内容 |
| --- | --- |
| アクセス権限の確認 | ユーザーの権限とURL に対応するルートの権限を確認 |
| CSRF トークン検証 | フォーム送信時のトークンを検証し、不正なリクエストを拒否 |
| エンティティアクセス確認 | そのユーザーがそのノードを削除する権限があるか確認 |
| 確認フォーム表示 | 「本当に削除しますか？」の確認画面を表示 |

データ管理:

| 段階 | 処理内容 |
| --- | --- |
| フック呼び出し | `hook_node_predelete` 等で他モジュールに削除を通知 |
| フィールドデータ削除 | ノードに紐づくフィールドの値を削除 |
| リビジョン削除 | 過去のリビジョンデータを削除 |
| パスエイリアス削除 | URL エイリアス（`/about` など）を削除 |
| キャッシュ無効化 | 関連するキャッシュをクリア |
| 検索インデックス更新 | 検索インデックスから該当ノードを除去 |

step4 のコードはこれらをすべて無視して、直接テーブルの行を消しているだけである。データの整合性が崩れるだけでなく、セキュリティ上の保護も一切ない。

フレームワークや CMS を使う理由の一つが、こうした処理を自分でゼロから実装しなくて済むことにある。
