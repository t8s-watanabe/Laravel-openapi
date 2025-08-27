<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class StoreUserSchema extends SchemaFactory
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('StoreUser')
            ->properties(
                Schema::string('name')
                    ->format(Schema::TYPE_STRING)
                    ->description('ユーザーのフルネーム')
                    ->example('山田 太郎'),
                Schema::string('email')
                    ->format(Schema::TYPE_STRING)
                    ->description('有効なメールアドレス')
                    ->example('taro.yamada@example.com'),
                Schema::string('password')
                    ->format(Schema::FORMAT_PASSWORD)
                    ->description('パスワード（8文字以上）')
                    ->example('password123')
            )
            // 全てのフィールドを必須項目としてマーク
            ->required('name', 'email', 'password');
    }

}
