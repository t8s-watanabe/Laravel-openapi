# Laravel 12 と OpenAPI

このプロジェクトは、Dockerコンテナ化環境で実行するように設定された、OpenAPI（Swagger）ドキュメント生成機能を備えたLaravel 12アプリケーションを実証するためのものです。

## プロジェクト構成

- `laravel/`: このディレクトリには、Laravel 12アプリケーションのコードが含まれています。
- `_docker/`: このディレクトリには、NginxおよびPHP-FPMコンテナ用のDockerfileと設定が含まれています。
- `.devcontainer/`: このディレクトリには、Visual Studio Code Dev Containersの設定が含まれており、一貫性のある隔離された開発環境を可能にします。

## はじめに

### 前提条件

- [Docker](https://www.docker.com/get-started)
- [Visual Studio Code](https://code.visualstudio.com/)
- Visual Studio Code用の[Remote - Containers拡張機能](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)

### 開発環境のセットアップ

このプロジェクトで作業する推奨の方法は、含まれているDev Container設定を使用することです。

1.  **リポジトリをクローンします:**
    ```bash
    git clone https://github.com/t8s/Laravel12-openapi.git
    cd Laravel12-openapi
    ```

2.  **Dev Containerで開きます:**
    - クローンしたリポジトリをVisual Studio Codeで開きます。
    - 「Reopen in Container」と表示されたら、ボタンをクリックします。これにより、開発コンテナがビルドおよび起動されます。

3.  **依存関係をインストールします:**
    コンテナが起動して実行されたら、VS Codeでターミナルを開き、次のコマンドを実行してプロジェクトの依存関係をインストールします:
    ```bash
    composer install
    npm install
    ```

## 使用方法

### Artisanコマンド

開発コンテナのターミナル内から任意の`artisan`コマンドを実行できます。たとえば、マイグレーションを実行するには:

```bash
php artisan migrate
```

### OpenAPIドキュメント

このプロジェクトでは、`tartanlegrand/laravel-openapi`パッケージを使用してAPIのOpenAPIドキュメントを生成します。

#### 開発手順

こちらに移動しました  
[OpenAPIドキュメント作成までの手順例](https://github.com/t8s-watanabe/Laravel-openapi/wiki/OpenAPI%E3%83%89%E3%82%AD%E3%83%A5%E3%83%A1%E3%83%B3%E3%83%88%E4%BD%9C%E6%88%90%E3%81%BE%E3%81%A7%E3%81%AE%E6%89%8B%E9%A0%86%E4%BE%8B)
