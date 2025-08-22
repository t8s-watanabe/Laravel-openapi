# Laravel-openapi
Laravel-openapi検証用

## OpenAPI ドキュメント生成パッケージ導入

このプロジェクトは [tartanlegrand/laravel-openapi](https://nova-edge.github.io/laravel-openapi/#installation) を利用して OpenAPI ドキュメントを生成します。

### インストール

```
composer require tartanlegrand/laravel-openapi
```

### サービスプロバイダー登録（自動登録されない場合のみ）

`config/app.php` の `providers` 配列に以下を追加：

```
Vyuldashev\LaravelOpenApi\OpenApiServiceProvider::class,
```

### 設定ファイルの公開

```
php artisan vendor:publish --provider="Vyuldashev\LaravelOpenApi\OpenApiServiceProvider" --tag="openapi-config"
```

### OpenAPI ドキュメントの生成

```
php artisan openapi:generate
```

### 参考リンク

- [公式ドキュメント](https://nova-edge.github.io/laravel-openapi/#installation)
- [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md)
- [Swagger Editor](https://editor.swagger.io/)
Laravel-openapi検証用
