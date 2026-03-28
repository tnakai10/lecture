# ステップ 6: Twig テンプレートの仕組み

## Twig とは

Drupal では、HTML のテンプレートに Twig というテンプレートエンジンを使っている。ステップ 5 では PHP ファイルをテンプレートとして使ったが、PHP テンプレートには問題がある。

- テンプレート内で何でもできてしまう（データベース操作すら書ける）
- HTML と PHP が混在して読みにくい
- セキュリティ対策（エスケープ）を書き忘れやすい

Twig は「テンプレートの中では表示に関することしかできない」ように制限したテンプレート言語。

## Twig の書き方

```twig
{# Drupal の Twig テンプレートの例 #}
{# templates/block.html.twig #}

<div{{ attributes }}>
  {{ title_prefix }}
  {% if label %}
    <h2{{ title_attributes }}>{{ label }}</h2>
  {% endif %}
  {{ title_suffix }}

  <div{{ content_attributes }}>
    {{ content }}
  </div>
</div>
```

- `{{ ... }}` : 変数の出力（自動的にエスケープされる）
- `{% ... %}` : 制御構文（if、for など）
- `{# ... #}` : コメント（出力されない）

## Twig は PHP にコンパイルされる

ここが重要。Twig テンプレートはブラウザに直接返されるのではない。

```
.html.twig ファイル
     ↓
[コンパイル] Twig エンジンが PHP コードに変換
     ↓
.php ファイル（キャッシュとして保存される）
     ↓
[実行] PHP エンジンが実行
     ↓
HTML 文字列（これがブラウザに返る）
```

実際にコンパイルされた PHP ファイルは `sites/default/files/php/twig/` に保存される。中身を見ると、Twig の構文が PHP の `yield` 文に変換されていることがわかる。

コンパイル前（Twig）:

```twig
<div{{ attributes }}>
  {% if label %}
    <h2>{{ label }}</h2>
  {% endif %}
</div>
```

コンパイル後（PHP / 簡略化した例）:

```php
yield "<div";
yield $this->escapeFilter($context["attributes"]);
yield ">";
if ($context["label"]) {
    yield "<h2>";
    yield $this->escapeFilter($context["label"]);
    yield "</h2>";
}
yield "</div>";
```

## 補足: yield とは

コンパイル後の PHP に登場する `yield` は、値を少しずつ返すための PHP の構文。通常の `return` は値を 1 つ返して関数が終了するが、`yield` は値を返した後も関数の実行が続く。

```php
// return: 一度に全部返す
function render(): string {
    return "<h1>タイトル</h1><p>本文</p>";
}

// yield: パーツごとに返す
function render(): Generator {
    yield "<h1>タイトル</h1>";
    yield "<p>本文</p>";
}
```

Twig が `yield` を使うのは、HTML 全体を一度にメモリに溜めるのではなく、パーツごとに出力できるためメモリ効率が良いから。

## 補足: コンパイル済みファイルの保存場所

コンパイルされた PHP ファイルは `sites/default/files/php/twig/` に保存されている。`step6/` のデモページにアクセスすると、実際のキャッシュファイルの中身を確認できる。

## なぜ PHP に変換するのか

- 2 回目以降のアクセスではコンパイル済み PHP を直接実行するため高速
- Twig の構文で書けることを制限しつつ、実行時は PHP のパフォーマンスを活かせる
- コンパイル済み PHP は自動的にキャッシュされ、テンプレートが変更されたときだけ再コンパイルされる
