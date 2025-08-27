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


/**
 * APIが返すJSONオブジェクトを記述することのみを目的とした専用クラス
 */
class UserSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
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
            );
    }
}
