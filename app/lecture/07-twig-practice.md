# ステップ 7: PHP テンプレートを Twig に置き換える

## やること

ステップ 5 の OOP 構造はそのままに、PHP テンプレートを Twig テンプレートに置き換える。

```
app/examples/
└── step7/
    ├── index.php              ← エントリポイント（Twig の autoload を追加）
    ├── Router.php             ← step5 と同じ
    ├── NodeController.php     ← Twig を使ってテンプレートを描画
    ├── Database.php           ← step5 と同じ
    ├── templates/
    │   └── node-list.html.twig  ← Twig テンプレート（PHP テンプレートから置換）
    └── cache/                 ← コンパイル済み PHP が自動生成される
```

## 確認方法

ブラウザで <https://drupal-project.ddev.site/examples/step7/> にアクセスし、step5 と同じノード一覧が表示されることを確認する。

## step5 との比較

step5 の `NodeController.php`:

```php
public function index(): void {
    $nodes = $this->db->getNodes();
    include __DIR__ . '/templates/node-list.php';
}
```

step7 の `NodeController.php`:

```php
public function index(): void {
    $nodes = $this->db->getNodes();
    echo $this->twig->render('node-list.html.twig', [
        'nodes' => $nodes,
    ]);
}
```

`include` でファイルを直接読み込む代わりに、Twig エンジンの `render()` メソッドにテンプレート名と変数を渡す。

## テンプレートの比較

step5（PHP テンプレート `templates/node-list.php`）:

```php
<?php foreach ($nodes as $node): ?>
  <tr>
    <td><?php echo htmlspecialchars($node['nid']); ?></td>
    <td><?php echo htmlspecialchars($node['title']); ?></td>
  </tr>
<?php endforeach; ?>
```

step7（Twig テンプレート `templates/node-list.html.twig`）:

```twig
{% for node in nodes %}
  <tr>
    <td>{{ node.nid }}</td>
    <td>{{ node.title }}</td>
  </tr>
{% endfor %}
```

- `htmlspecialchars` を書く必要がない（Twig が自動エスケープする）
- PHP のタグ（`<?php ... ?>`）が不要で、HTML に近い見た目になる
- テンプレート内でデータベース操作などの危険なコードが書けない

## ポイント

- Twig テンプレートは表示に専念し、ロジックを書けないように制限されている
- エスケープが自動化されるため、XSS 対策の漏れが起きにくい
- Drupal のテーマ開発も、この Twig テンプレートの仕組みで行う

## キャッシュの確認

`step7/cache/` ディレクトリを見ると、キャッシュされているのは HTML ファイルではなく PHP ファイルであることがわかる。これはステップ 6 で説明した、Drupal の `sites/default/files/php/twig/` と同じ仕組み。

## なぜ HTML ではなく PHP をキャッシュするのか

同じテンプレートでも、渡されるデータによって出力される HTML は毎回変わる。例えばノードが 3 件のときと 10 件のときでは生成される HTML が異なる。ログイン状態による出し分けがあれば、さらにバリエーションが増える。HTML をキャッシュしようとすると、データの組み合わせごとに無数の HTML ファイルが必要になってしまう。

一方、コンパイル済み PHP は「データを受け取って HTML を組み立てるレシピ」。テンプレート 1 つにつき PHP ファイル 1 つで済む。

```text
1 回目のアクセス:
  .html.twig → [コンパイル] → .php（キャッシュに保存）→ [実行 + データ] → HTML

2 回目以降のアクセス:
  .php（キャッシュから読み込み）→ [実行 + データ] → HTML
```

さらに、ステップ 6 で説明した `yield` がここで効いてくる。キャッシュされた PHP はジェネレータとして動作し、HTML を一度にメモリに溜め込むのではなく、パーツごとに少しずつ生成する。コンパイルのコストを省きつつ、実行時のメモリ効率も良い仕組みになっている。
