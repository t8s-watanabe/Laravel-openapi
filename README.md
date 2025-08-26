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

マイグレーション→Model→コントローラまでの実装はlaravel標準の手順参照。

##### Laravel-OpenAPI 関係図（簡潔）

```txt
App\Http\Controllers\Api\OpenApiUserController
    ├─ uses -> App\Models\User
    ├─ returns -> App\Http\Resources\UserResource (show)
    ├─ returns -> App\Http\Resources\UserCollection (index)
    ├─ OpenAPI 200 -> App\OpenApi\Responses\UserResponse
    │     └─ references -> App\OpenApi\Schemas\UserSchema
    ├─ OpenAPI 200 (collection) -> App\OpenApi\Responses\UserCollectionResponse
    │     └─ references -> App\OpenApi\Schemas\UserSchema
    └─ OpenAPI 404 -> App\OpenApi\Responses\NotFoundResponse

App\OpenApi\Schemas\UserSchema
    └─ defines -> User object schema (id, name, email, created_at, updated_at)

App\OpenApi\Responses\UserResponse
    └─ content -> UserSchema::ref()

App\OpenApi\Responses\UserCollectionResponse
    └─ content -> { data: [ UserSchema::ref() ], links, meta }

App\OpenApi\Responses\NotFoundResponse
    └─ defines -> 404 response
```

##### 1. APIリソースの作成

```bash
# APIリソースの生成
php artisan make:resource UserResource
php artisan make:resource UserCollection
```

`UserResource`の実装：  
`UserResource`の必要性 → APIの「公開契約」を定義するための重要なステップです。Eloquentモデルを直接返すと、データベースの構造（カラム名など）が外部にそのまま公開されてしまいます。  
UserResourceはモデルと最終的なJSONレスポンスの間に「変換レイヤー」を設けることで、公開したいデータだけを選択し、キーの名前を変更し、さらには追加のデータを付与することを可能にします 。

```php
// app/Http/Resources/UserResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

`UserCollection`の実装：  
`UserCollection`の必要性 → 単一のリソース（UserResource）が個々のユーザーデータを整形するのに対し、UserCollectionはユーザーのリスト、特にページネーションされた結果を扱うために特化しています。  
これにより、dataキーでユーザーリストをラップするだけでなく、ページネーション情報（linksやmetaなど）をレスポンスに含めることが可能になります 。個別のリソースコレクションクラスを用意することで、コレクション全体に関連するメタデータを柔軟に追加できるようになります。

```php
// app/Http/Resources/UserCollection.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
```

##### 2. OpenAPIスキーマの作成

```bash
# スキーマファクトリの生成
php artisan openapi:make-schema UserSchema
```

`UserSchema`の実装：  

```php
// app/OpenApi/Schemas/UserSchema.php
namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class UserSchema extends SchemaFactory implements Reusable
{
    public function build(): SchemaContract
    {
        return Schema::object('User')
            ->properties(
                Schema::integer('id')
                    ->description('ユーザーID')
                    ->example(1),
                Schema::string('name')
                    ->description('ユーザー名')
                    ->example('John Doe'),
                Schema::string('email')
                    ->description('メールアドレス')
                    ->example('john.doe@example.com'),
                Schema::string('created_at')
                    ->format(Schema::FORMAT_DATE_TIME)
                    ->description('作成日時')
                    ->example('2024-01-01T12:00:00Z'),
                Schema::string('updated_at')
                    ->format(Schema::FORMAT_DATE_TIME)
                    ->description('更新日時')
                    ->example('2024-01-01T13:00:00Z')
            )
            ->required('id', 'name', 'email', 'created_at', 'updated_at');
    }
}
```

##### 3. レスポンスファクトリの作成

```bash
# レスポンスファクトリの生成
php artisan openapi:make-response UserResponse
php artisan openapi:make-response UserCollectionResponse
php artisan openapi:make-response NotFoundResponse
```

`UserResponse`の実装：  

```php
// app/OpenApi/Responses/UserResponse.php
namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class UserResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')
            ->content(
                MediaType::json()->schema(UserSchema::ref())
            );
    }
}
```

`UserCollectionResponse`の実装：  

```php
// app/OpenApi/Responses/UserCollectionResponse.php
namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class UserCollectionResponse extends ResponseFactory
{
    public function build(): Response
    {
        $response = Schema::object()->properties(
            Schema::array('data')->items(UserSchema::ref()),
            Schema::object('links')->properties(
                Schema::string('first')->nullable(),
                Schema::string('last')->nullable(),
                Schema::string('prev')->nullable(),
                Schema::string('next')->nullable()
            ),
            Schema::object('meta')->properties(
                Schema::integer('current_page'),
                Schema::integer('from')->nullable(),
                Schema::integer('last_page'),
                Schema::string('path'),
                Schema::integer('per_page'),
                Schema::integer('to')->nullable(),
                Schema::integer('total')
            )
        );

        return Response::ok()->description('Successful response')
            ->content(MediaType::json()->schema($response));
    }
}
```

`NotFoundResponse`の実装：  

```php
// app/OpenApi/Responses/NotFoundResponse.php
namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class NotFoundResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::notFound()->description('Resource not found');

    }
}
```

##### 4. コントローラーへのアトリビュート追加

```php
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;
use App\OpenApi\V1\Responses\UserResponse;
use App\OpenApi\V1\Responses\NotFoundResponse;
use App\OpenApi\V1\Responses\UserCollectionResponse;

#[OpenApi\PathItem]
class UserController extends Controller
{
    #[OpenApi\Operation(tags: ['Users'])]
    #[OpenApi\Response(factory: UserCollectionResponse::class, statusCode: 200)]
    public function index()
    {
        // UserResourceを使用してコレクション内の各ユーザーを変換し、dataキーでラップ
        return new UserCollection(User::paginate());
    }

    #[OpenApi\Operation(tags: ['Users'])]
    #[OpenApi\Response(factory: UserResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    public function show(User $user)
    {
        return new UserResource($user);
    }
}
```

##### 5. OpenAPIドキュメントの生成

```bash
php artisan openapi:generate >docs/openapi.yaml
```

生成されたファイルは `docs/openapi.yaml` に保存されます。
