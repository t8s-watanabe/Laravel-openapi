<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use App\OpenApi\Schemas\InternalServerErrorSchema;

class InternalServerErrorResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::internalServerError()
            ->description('サーバー内部エラー')
            ->content(
                MediaType::json()->schema(InternalServerErrorSchema::ref())
            );
    }
}
