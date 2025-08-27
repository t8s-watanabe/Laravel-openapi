<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class AcceptHeaderParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::header()
                ->name('Accept')
                ->description('レスポンスのフォーマットを指定します。通常は "application/json" を使用します。')
                ->required(true)
                ->schema(Schema::string()
                    ->default('application/json')
                ),

        ];
    }
}
