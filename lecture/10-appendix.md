# 補足: 理解を深めるための追加トピック

## index.php がすべてのリクエストを受ける仕組み

Drupal には `index.php` が 1 つしかない。`/node/1` にアクセスしても、`/user/login` にアクセスしても、すべて `index.php` が処理する。

これは Web サーバーの設定で実現されている。この DDEV 環境の Nginx 設定（`/etc/nginx/sites-enabled/nginx-site.conf`）には以下のようなルールがある。

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

`try_files` は Nginx のディレクティブで、以下の順番でファイルを探す:

1. `$uri` : リクエストされた URL に一致するファイルがあればそれを返す
2. `$uri/` : ディレクトリがあればその中の index ファイルを返す
3. `/index.php?$query_string` : どちらも見つからなければ `index.php` に転送する

これにより:

- `/about.html` というファイルが実在すればそのファイルを返す
- `/node/1` というファイルは存在しないので `index.php` に転送される
- `index.php`（Drupal）が URL を解析して適切なコンテンツを返す

このパターンを「フロントコントローラパターン」と呼ぶ。すべてのリクエストが `index.php` という 1 つの入口を通るため、認証・ルーティング・ログなどの共通処理を一元管理できる。

なお、practice ファイル（step1〜7）は `try_files` の `$uri` で実ファイルとして見つかるため、Drupal の `index.php` を経由せず各ファイルが直接実行される。

```text
Drupal:    /node/1           → Nginx → ファイルなし → index.php → Drupal カーネル → HTML
practice:  /practice/step3/  → Nginx → ファイルあり → step3/index.php → HTML
```

## Drupal のキャッシュ

Drupal は処理の各段階でキャッシュを活用している。

- Twig コンパイルキャッシュ: `.html.twig` → `.php` の変換結果をファイルとして保存
- レンダーキャッシュ: 生成済み HTML の断片をデータベースに保存
- ページキャッシュ: ページ全体の HTML をキャッシュ（匿名ユーザー向け）
- Dynamic Page Cache: ログインユーザー向けのページキャッシュ

これらのキャッシュにより、同じページへの 2 回目以降のアクセスでは「データベースからデータを取得 → テンプレートで HTML を生成」という処理の多くをスキップし、高速に応答できる。

## なぜ CMS を使うのか

静的 HTML でも Web サイトは作れる。しかし CMS（Drupal）を使う理由は:

- コンテンツの作成・編集を非エンジニアでもできる（管理画面）
- ユーザー管理、権限管理が組み込まれている
- 多言語対応、SEO、アクセシビリティなどが標準機能
- モジュール（プラグイン）で機能を拡張できる
- セキュリティアップデートがコミュニティから提供される
- 1 つのテーマ（テンプレート群）でサイト全体のデザインを統一管理できる
