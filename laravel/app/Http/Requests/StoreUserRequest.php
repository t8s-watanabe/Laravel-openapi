<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * リクエストに適用される検証ルールを取得する。
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // App\OpenApi\Schemas\StoreUserSchema に合わせたバリデーションルールを定義
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)],
        ];
    }

    /**
     * 属性名のカスタマイズ
     */
    public function attributes()
    {
        return [
            'name'     => 'ユーザー名',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }

    /**
     * バリデーションエラーメッセージのカスタマイズ
     */
    public function messages()
    {
        return [
            'required' => ':attributeは必須です',
            'unique'   => ':attribute は重複不可です'
        ];
    }
}
