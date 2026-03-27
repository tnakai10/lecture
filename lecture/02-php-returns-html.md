# ステップ 2: PHP ファイルが HTML を返す仕組みを確認する

## やること

`index.php` を作成し、ブラウザからアクセスする。

```
app/practice/
  step2-php/
    index.php
```

```php
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Step 2: PHP</title>
</head>
<body>
  <h1>こんにちは！</h1>
  <p>これは PHP ファイルですが、中身は HTML と同じです。</p>
</body>
</html>
```

## 確認方法

ブラウザでアクセスし、ステップ 1 と同じように HTML が表示されることを確認する。ブラウザの「ページのソースを表示」で確認すると、返ってきているのは普通の HTML であることがわかる。

## なぜ index.php なのに HTML が返るのか

ここが重要なポイント。この DDEV 環境では Web サーバーに Nginx、PHP の実行に PHP-FPM を使っている。

1. ブラウザが `index.php` をリクエストする
2. Nginx は拡張子が `.php` であることを認識する
3. Nginx はファイルをそのまま返すのではなく、PHP-FPM に処理を転送する
4. PHP-FPM がファイルを実行する
5. PHP-FPM の実行結果（出力）を Nginx がブラウザに返す

PHP ファイルの中に PHP コード（`<?php ... ?>`）がなければ、ファイルの中身がそのまま出力される。つまり、HTML だけ書かれた PHP ファイルは、HTML ファイルと同じ結果になる。

`.php` ファイルの場合（Nginx + PHP-FPM）:

```
[ブラウザ] → リクエスト → [Nginx] → [PHP-FPM] → 実行結果 → [Nginx] → レスポンス → [ブラウザ]
```

`.html` ファイルの場合（ステップ 1 で確認した通り）:

```
[ブラウザ] → リクエスト → [Nginx] → ファイルの中身をそのまま返す → [ブラウザ]
```

つまり、`.php` の場合は Nginx と ブラウザの間に PHP-FPM による「実行」という処理が挟まる。`.html` の場合は Nginx が単独で処理を完結する。
