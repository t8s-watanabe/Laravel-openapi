<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\StoreUserRequest;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\UserCollectionResponse;
use App\OpenApi\Responses\UserResponse;
use App\OpenApi\Responses\ValidationErrorResponse;
use App\OpenApi\RequestBodies\StoreUserRequestBody;

/**
 * OpenAPIの仕様書を生成するUserController
 */
#[OpenApi\PathItem]
class OpenApiUserController extends Controller
{
    /**
     * ユーザーデータの一覧を取得
     *
     */
    #[OpenApi\Operation(tags: ['Users'])]
    #[OpenApi\Response(factory: UserCollectionResponse::class, statusCode: 200)]
    public function index()
    {
        // UserResourceを使用してコレクション内の各ユーザーを変換し、dataキーでラップ
        return new UserCollection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OpenApi\Operation(tags: ['Users'])]
    #[OpenApi\RequestBody(factory: StoreUserRequestBody::class)]
    #[OpenApi\Response(factory: UserResponse::class, statusCode: 201)]
    #[OpenApi\Response(factory: ValidationErrorResponse::class, statusCode: 422)]
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * ユーザーデータの詳細を取得
     */
    #[OpenApi\Operation(tags: ['Users'])]
    #[OpenApi\Response(factory: UserResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
