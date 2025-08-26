<?php

namespace App\OpenApi\Responses;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

/**
 * aravelのpaginate()が生成するJSON構造に合わせて、ページネーションされたレスポンスをモデル化する専用クラス
 */
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
