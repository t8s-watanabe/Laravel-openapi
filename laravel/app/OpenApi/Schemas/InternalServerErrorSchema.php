<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class InternalServerErrorSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('InternalServerError')
            ->properties(
                Schema::string('message')
                    ->description('汎用エラーメッセージ')
                    ->example('Internal Server Error'),
                Schema::string('errorId')
                    ->description('ログ照会用のエラー識別子（サポート用）')
                    ->example('3fa85f64-5717-4562-b3fc-2c963f66afa6')
            );
    }
}
