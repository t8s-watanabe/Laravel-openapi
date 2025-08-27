<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserCollectionSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

/**
 * laravelのpaginate()が生成するJSON構造に合わせて、ページネーションされたレスポンスをモデル化する専用クラス
 */
class UserCollectionResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('成功時のレスポンス')
            ->content(MediaType::json()->schema(UserCollectionSchema::ref()));
    }
}
