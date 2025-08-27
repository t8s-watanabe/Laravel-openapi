<?php

namespace App\OpenApi\RequestBodies;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;
use App\OpenApi\Schemas\StoreUserSchema;

class StoreUserRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create('StoreUser')
            ->description('ユーザー作成のためのリクエストボディ')
            ->content(
                MediaType::json()->schema(StoreUserSchema::ref())
            )
            ->required();
    }
}
