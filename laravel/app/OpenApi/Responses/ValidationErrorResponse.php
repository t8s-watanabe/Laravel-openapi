<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;
use App\OpenApi\Schemas\ValidationErrorSchema;

class ValidationErrorResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::unprocessableEntity()
            ->description('バリデーションエラー')
            ->content(
                MediaType::json()->schema(ValidationErrorSchema::ref())
            );
    }
}
