# Composer template for Drupal projects

[![Merge upstream branches](https://github.com/studioumi/drupal-project/actions/workflows/scheduled_sync.yml/badge.svg)](https://github.com/studioumi/drupal-project/actions/workflows/scheduled_sync.yml)
[![CI](https://github.com/studioumi/drupal-project/actions/workflows/ci.yml/badge.svg)](https://github.com/studioumi/drupal-project/actions/workflows/ci.yml)

composer create-projectによってDrupal10.xの初期構築を行うパッケージ。

## 講義資料

本プロジェクトには Drupal の仕組みを段階的に学ぶための講義資料が含まれています。静的 HTML の知識がある人向けに、Drupal がどのようにページを表示しているかを理解するための資料です。

資料は [app/lecture/](app/lecture/) ディレクトリに配置されています。DDEV 環境を起動している場合は、ブラウザから整形された形式で閲覧できます。

```
https://drupal-project.ddev.site/lecture/
```

### 目次

1. [前提知識の確認](app/lecture/00-index.md)
2. [静的 HTML が返る仕組みを確認する](app/lecture/01-static-html.md)
3. [PHP ファイルが HTML を返す仕組みを確認する](app/lecture/02-php-returns-html.md)
4. [PHP には様々なロジックが書けることを確認する](app/lecture/03-php-logic.md)
5. [1 つのファイルに何でも書けてしまう問題](app/lecture/04-single-file-problem.md)
6. [OOP（オブジェクト指向）で構造化する](app/lecture/05-oop.md)
7. [Twig テンプレートの仕組み](app/lecture/06-twig.md)
8. [PHP テンプレートを Twig に置き換える](app/lecture/07-twig-practice.md)
9. [Drupal がページを返すまでの全体像](app/lecture/08-drupal-overview.md)
10. [まとめ: 各ステップの繋がり](app/lecture/09-summary.md)
11. [補足: 理解を深めるための追加トピック](app/lecture/10-appendix.md)

### 講義の進め方

1. [00-index.md](app/lecture/00-index.md) から順番に読み進めてください
2. 各ステップには講義資料と実習が含まれています
3. DDEV 環境を起動した状態で実習を行うことを推奨します

## 使用方法

最初に [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) をインストールしてください。

下記のコマンドでプロジェクトを作成します。

```bash
# {some-dir} は Drupal をインストールするディレクトリ.
composer create-project studioumi/drupal-project:10.x-dev {some-dir} --no-interaction
```

プロジェクト作成後、インストールディレクトリへ移動しDrupalの初期インストールを実行します。

```bash
cd {some-dir}
drush site:install --account-name=admin --account-mail=foo@example.com --account-pass=pass --locale=ja --db-url=mysql://user:password@host:port/dbname
```

初期インストール後、 `settings.php` を変更しgitの初期化を行います。

```bash
git init
git commit -m "initial commit."
```

## DDEVを実行

以下コマンドでDDEV環境が起動します。
DDEVのインストールは事前に行ってください。( https://ddev.readthedocs.io/en/stable/ )

```bash
ddev start
```

## その他ライブラリ導入方法

コントリビュートモジュールやその他ライブラリをインストールする場合
`composer require ...` コマンドで導入できます。

```bash
cd some-dir
composer require drupal/devel:~1.0
```

## Drupal コアのアップデート

1. `composer update` を利用し、パッケージをアップデートします

```bash
composer update drupal/core-* --with-dependencies
```

2. `git diff` で差分の確認を行います。その際、 `.htaccess` や `robots.txt` 等のファイルも更新される為
   必要に応じて差分の取り込みを行います。

## コア及びコントリビュートモジュールのパッチ適用

コア等の挙動に問題があり、パッチを当てる必要がある場合 `composer.json` へ適用するパッチを記載します。
これは [composer-patches](https://github.com/cweagans/composer-patches) によって自動的にパッチが適用されます。

```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```

## PHPのバージョンを固定する方法

以下コマンドで実行するPHPのバージョンを固定することが出来ます。

```bash
composer config platform.php 8.1
```

## フロントエンド開発用のパッケージ

本パッケージにはカスタムモジュール/テーマのフロントエンド開発を楽にするためのタスクランナーとリンターが含まれています。これらを利用するにはローカル環境で Node.js と Yarn を使えるようにしてください。

### セットアップ

次のコマンドを実行して必要な Node.js のパッケージをインストールします。

```bash
yarn
```

### stylelint

SCSS で書かれたファイルを Drupal のコーディングスタンダードをベースとしたものに則ってチェックします。

```bash
npx stylelint <file_name>

# カスタムモジュールを一括で stylelint する例
npx stylelint app/modules/custom/**/*.scss
```

### ESLint

ES6 の JavaScript で書かれたファイルを Drupal のコーディングスタンダードに則ってチェックします。

```bash
npx eslint <file_name>

# カスタムモジュールを一括で eslint する例
npx eslnt app/modules/custom/**/*.es6.js
```

### Gulp.js

Browsersync の起動や、SASS(SCSS) および ES6 で書かれた JavaScript のトランスパイルなどのタスクを自動化するタスクランナーです。

```bash
npx gulp [tasks]
```

`gulp` 実行時にタスクを指定しなかった場合は Browsersync が起動し、SCSS および JavaScript(ES6) のファイルを監視して更新があった時にトランスパイルします。

利用可能なタスク:

|タスク|説明|
|-|-|
| `build:scss` | カスタムモジュール・テーマ内にある、SCSSファイル(拡張子が .scss のもの)をCSSにトランスパイルします。 |
| `build:js` | カスタムモジュール・テーマ内にある、ES6のJavaScriptファイル(拡張子が .es6.js のもの)をにトランスパイルします。 |
| `build` | ビルドタスクを一括して行います。 |
| `lint:scss` | カスタムモジュール・テーマ内にある、SCSSファイルを stylelint でチェックします。 |
| `lint:js` | カスタムモジュール・テーマ内にある、ES6のJavaScriptファイルを ESLint でチェックします。 |
| `lint` | リンタータスクを一括して行います。 |
| `watch:scss` | カスタムモジュール・テーマ内にある、SCSSファイルを監視して更新があった際にビルドタスクを実行します。 |
| `watch:js` | カスタムモジュール・テーマ内にある、ES6のJavaScriptファイルを監視して更新があった際にビルドタスクを実行します。 |
| `watch:twig` | カスタムモジュール・テーマ内にある、Twig テンプレートを監視して更新があった際にブラウザをリロードします。 |
| `watch` | 監視タスクを一括して行います。 |

#### 設定

各タスクのデフォルトのオプションを変更したい場合は、 gulpfile.js/config/example.local.yml のファイルを gulpfile.js/config/local.yml にコピーして設定をオーバーライドます

Browsersync のポートを変更したい場合は次の様に定義します。

```yaml
browsersync:
  port: 8080
```

デフォルトの設定やその他のオプションについて詳しく知りたい場合は、 gulpfile.js/config/default.yml に書かれたコメントを参照してください。

## その他

本プロジェクトは [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) のフォークプロジェクトです。
詳細な内容はそちらを参照ください。

## 主な変更点

- `drupal/core` -> `drupal/core-recomended` への置き換え
- [drupal-composer/drupal-paranoia](https://packagist.org/packages/drupal-composer/drupal-paranoia) の利用
- [DDEV](https://ddev.readthedocs.io/en/stable/) の実行環境
