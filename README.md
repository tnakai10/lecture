# Drupal 講義・実習プロジェクト

静的 HTML の知識がある人向けに、Drupal がどのようにページを表示しているかを段階的に学ぶための講義・実習プロジェクトです。

## セットアップ

[DDEV](https://ddev.readthedocs.io/en/stable/) をインストールした上で、以下のコマンドで環境を起動します。

```bash
ddev start
```

Drupal のサイトインストールを行うには、以下のコマンドを実行します。

```bash
ddev drush site:install --account-name=admin --account-pass=admin --locale=ja -y
```

管理者としてログインするには、以下のコマンドでワンタイムログインURLを生成できます。

```bash
ddev drush user:login
```

## 講義資料

資料は [app/lecture/](app/lecture/) ディレクトリに配置されています。
DDEV 環境を起動している場合は、ブラウザから整形された形式で閲覧できます。

https://drupal-project.ddev.site/lecture/

## コード例

講義で使用するサンプルコードは [app/examples/](app/examples/) ディレクトリに配置されています。
各ステップは講義資料と対応しています。
