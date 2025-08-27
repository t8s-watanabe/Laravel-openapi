<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        // ValidationException は API リクエスト時に JSON を返す（422）
        $this->renderable(function (ValidationException $e, $request) {
            if (! $request->expectsJson()) {
                // 非API は通常のフォームリダイレクトに任せる（親処理へ）
                return null;
            }

            $payload = [
                'message' => $e->getMessage() ?: 'The given data was invalid.',
                'errors'  => $e->errors(),
            ];

            return response()->json($payload, 422);
        });

        // 例外をログ送信・外部サービスに送る
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                Log::error($e->getMessage(), ['exception' => $e]);
                // 例: Sentry::captureException($e);
            }
        });
    }

    /**
     * フォールバック：register() の renderable が未処理の例外は親に委ねる
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
