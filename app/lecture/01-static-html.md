# ステップ 1: 静的 HTML が返る仕組みを確認する

## やること

別途ディレクトリを作成し、`index.html` を配置してブラウザからアクセスする。

```
app/examples/
└── step1/
    └── index.html
```

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 1: 静的 HTML</title>
</head>
<body>
  <h1>こんにちは！</h1>
  <p>これは静的な HTML ファイルです。</p>
</body>
</html>
```

## 確認方法

- <https://drupal-project.ddev.site/examples/step1/index.html> にアクセスし、HTML が表示されることを確認する
- <https://drupal-project.ddev.site/examples/step1/> でも同じ結果になることを確認する

### なぜディレクトリ名だけでアクセスできるのか

URL の末尾がディレクトリの場合、Nginx は自動的にそのディレクトリ内の `index.html` や `index.php` を探して返す。これを「ディレクトリインデックス」という。

つまり、以下の 2 つは同じ意味になる:

- `/examples/step1/` → Nginx が `/examples/step1/index.html` を探して返す
- `/examples/step1/index.html` → 直接そのファイルを返す

この仕組みがあるため、URL にファイル名を書かなくてもページが表示される。

## ポイント

- Web サーバー（Nginx）は `index.html` ファイルをそのまま返している
- サーバーは中身を一切変更しない。ファイルの内容 = ブラウザに届く内容
- これが「静的」と呼ばれる理由

```
[ブラウザ] → リクエスト → [Nginx] → ファイルの中身をそのまま返す → [ブラウザ]
```

## 補足: Nginx とは

この DDEV 環境では、Web サーバーとして Nginx（エンジンエックス）を使っている。

Nginx の役割
- ブラウザからの HTTP リクエストを受け取り、適切なファイルを探してレスポンスとして返す
- `.html` や `.css`、画像ファイルなどの静的ファイルであれば、Nginx が単独でファイルを読み取ってブラウザに返す

```
[Nginx]
  - HTTP リクエストを受け取る
  - 静的ファイル（.html, .css, .js, 画像など）をそのまま返す
```
