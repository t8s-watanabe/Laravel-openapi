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

### アプリケーションの実行

アプリケーションは実行され、[http://localhost:8080](http://localhost:8080)でアクセスできます。

## 使用方法

### Artisanコマンド

開発コンテナのターミナル内から任意の`artisan`コマンドを実行できます。たとえば、マイグレーションを実行するには:

```bash
php artisan migrate
```

### OpenAPIドキュメント

このプロジェクトでは、`tartanlegrand/laravel-openapi`パッケージを使用してAPIのOpenAPIドキュメントを生成します。

ドキュメントを生成するには、開発コンテナ内で次のコマンドを実行します:

```bash
php artisan openapi:generate
```

これにより、`laravel/storage/api-docs/`ディレクトリに`openapi.json`ファイルが作成されます。その後、[Swagger UI](https://swagger.io/tools/swagger-ui/)や[Stoplight Elements](https://stoplight.io/open-source/elements)などのツールを使用してドキュメントを表示できます。
