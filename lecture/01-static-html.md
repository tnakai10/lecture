# ステップ 1: 静的 HTML が返る仕組みを確認する

## やること

別途ディレクトリを作成し、`index.html` を配置してブラウザからアクセスする。

```
app/practice/
  step1-html/
    index.html
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

DDEV 環境であれば、Web サーバーのドキュメントルート配下にファイルを置き、ブラウザでアクセスして表示を確認する。

## ポイント

- Web サーバー（Nginx）は `index.html` ファイルをそのまま返している
- PHP は一切関与しない。Nginx が単独でファイルを読み取り、ブラウザに返す
- サーバーは中身を一切変更しない。ファイルの内容 = ブラウザに届く内容
- これが「静的」と呼ばれる理由

```
[ブラウザ] → リクエスト → [Nginx] → ファイルの中身をそのまま返す → [ブラウザ]
```
