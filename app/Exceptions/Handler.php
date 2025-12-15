<?php

namespace App\Exceptions;

use App\Http\Services\TelegramLogger;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $message = "<b>🚨 Laravel Exception</b>\n\n";
            $message .= "<b>Message:</b> {$e->getMessage()}\n\n";
            $message .= "<b>File:</b> {$e->getFile()}\n";
            $message .= "<b>Line:</b> {$e->getLine()}\n\n";
            $message .= "<b>URL:</b> " . request()->fullUrl() . "\n";
            $message .= "<b>Method:</b> " . request()->method() . "\n";
            $message .= "<b>IP:</b> " . request()->ip() . "\n";
            $message .= json_encode($e->getTrace());

            TelegramLogger::send($message);
        });
    }
}
