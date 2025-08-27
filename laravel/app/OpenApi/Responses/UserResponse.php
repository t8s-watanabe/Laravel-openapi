<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class UserResponse extends ResponseFactory
{
    public function build(): Response
    {
        // 200レスポンスを定義し、そのコンテンツとして作成したUserSchemaを参照
        return Response::ok()->description('成功時のレスポンス')
            ->content(
                MediaType::json()->schema(UserSchema::ref())
            );
    }
}
