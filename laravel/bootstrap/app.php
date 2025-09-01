<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AssignRequestId;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // requestidを付与するミドルウェアを追加
        $middleware->append(AssignRequestId::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 例外ハンドラのカスタマイズ
        $exceptions->render(function (Throwable $e, Request $request){
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
            // エラー識別用 ID を生成
            $errorId = $request->attributes->get('requestid');
            // 詳細はログにのみ出す
            Log::error(sprintf('Unhandled exception [%s]: %s', $errorId, $e->getMessage()), [
                'errorId' => $errorId,
                'exception' => $e,
            ]);
            return response()->json([
                'message' => 'Internal Server Error',
                'errorId' => $errorId,
            ], 500);
        });

        $exceptions->report(function (Throwable $e): bool {
            if ($e instanceof ValidationException) {
                Log::warning('Validation exception: '.$e->getMessage(), [
                    'errors' => $e->errors(),
                    'exception' => $e,
                ]);
                return false;
            }
            return true;
        });
    })->create();
