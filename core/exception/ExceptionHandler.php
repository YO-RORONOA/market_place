<?php
// File: core/exception/ExceptionHandler.php

namespace App\core\exception;

use App\core\Application;
use App\core\Response;
use Exception;
use Throwable;

/**
 * A centralized exception handler for the application.
 * This class handles all kinds of exceptions and renders appropriate views.
 */
class ExceptionHandler
{
    
    public static function register()
    {
        set_exception_handler([self::class, 'handle']);
        set_error_handler([self::class, 'handleError']);
    }

    /**
     * Convert PHP errors to exceptions.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file File where error occurred
     * @param int $line Line where error occurred
     * @throws \ErrorException
     */
    public static function handleError($level, $message, $file, $line)
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    
    public static function handle(Throwable $exception)
    {
        self::logException($exception);
        
        $app = Application::$app ?? null;
        
        if (!$app) {
            http_response_code(500);
            echo "An unexpected error occurred. Please try again later.";
            exit;
        }
        
        $response = $app->response;
        
        if ($exception instanceof NotFoundException) {
            return self::renderErrorPage($response, 404, 'Not Found', $exception);
        }
        
        if ($exception instanceof ForbiddenException) {
            return self::renderErrorPage($response, 403, 'Forbidden', $exception);
        }
        
        if ($exception instanceof BadRequestException) {
            return self::renderErrorPage($response, 400, 'Bad Request', $exception);
        }
        
        if ($exception instanceof DatabaseException) {
            return self::renderErrorPage($response, 500, 'Database Error', $exception);
        }
        
        if ($exception instanceof ValidationException) {
            return self::renderErrorPage($response, 422, 'Validation Error', $exception);
        }
        
        return self::renderErrorPage($response, 500, 'Server Error', $exception);
    }
    
    /**
     * Render an error page based on exception type.
     *
     * @param Response $response
     * @param int $statusCode
     * @param string $title
     * @param Throwable $exception
     * @return void
     */
    private static function renderErrorPage(Response $response, int $statusCode, string $title, Throwable $exception)
    {
        $response->statusCode($statusCode);
        
        $isDebug = $_ENV['APP_ENV'] === 'development';
        
        if ($isDebug) {
            echo self::renderDetailedError($exception, $title, $statusCode);
            return;
        }
        
        try {
            echo Application::$app->router->renderView('errors/error', [
                'exception' => $exception,
                'title' => $title,
                'code' => $statusCode,
                'message' => $exception->getMessage(),
                'isDebug' => $isDebug
            ]);
        }  catch (Throwable $e) {
            if ($_ENV['APP_ENV'] === 'development') {
                echo "Error while rendering error view: " . $e->getMessage();
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
            } else {
                // Simple message in production
                echo "An error occurred. Please try again later.";
            }
        }
        }



    private static function logException(Throwable $exception)
    {
        $logDir = Application::$ROOT_DIR . '/logs';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        $message = "[" . date('Y-m-d H:i:s') . "] " .
            get_class($exception) . ": " . $exception->getMessage() .
            " in " . $exception->getFile() . " on line " . $exception->getLine() .
            "\nStack trace: " . $exception->getTraceAsString() . "\n\n";
        
        error_log($message, 3, $logDir . '/app-errors.log');
        
       
    }
    
   
    private static function renderDetailedError(Throwable $exception, string $title, int $statusCode)
    {
        $trace = $exception->getTraceAsString();
        $type = get_class($exception);
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error $statusCode: $title</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; max-width: 1200px; margin: 0 auto; }
                h1 { color: #e74c3c; border-bottom: 1px solid #eee; padding-bottom: 10px; }
                h2 { color: #3498db; margin-top: 20px; }
                pre { background: #f8f8f8; padding: 15px; border-radius: 4px; overflow-x: auto; }
                .message { background: #f8d7da; border-left: 4px solid #e74c3c; padding: 15px; margin: 20px 0; }
                .file { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 20px 0; }
                .trace { background: #f8f9fa; border-left: 4px solid #6c757d; padding: 15px; margin: 20px 0; overflow-x: auto; }
                .note { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <h1>Error $statusCode: $title</h1>
            <p class="note">This detailed error page is displayed because the application is in development mode. In production, users would see a friendly error page.</p>
            
            <h2>Exception</h2>
            <div class="message">
                <strong>Type:</strong> $type<br>
                <strong>Message:</strong> $message
            </div>
            
            <h2>Location</h2>
            <div class="file">
                <strong>File:</strong> $file<br>
                <strong>Line:</strong> $line
            </div>
            
            <h2>Stack Trace</h2>
            <div class="trace">
                <pre>$trace</pre>
            </div>
            
            <h2>Request Details</h2>
            <div class="trace">
                <pre>URI: {$_SERVER['REQUEST_URI']}\nMethod: {$_SERVER['REQUEST_METHOD']}</pre>
            </div>
        </body>
        </html>
        HTML;
    }
}

        
    
    
   