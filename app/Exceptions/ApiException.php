<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Psy\Util\Json;

class ApiException extends Exception
{
    // обработка ошибок
    // Кастомное исключение для стандартизации ответов API
    // Позволяет нам выбрасывать ошибки из любого места приложения (контроллеры, сервисы)
    // и позволяет автоматически преобразовывать их в единый JSON-формат с нужным HTTP-статусом

    // 200, 201, 300, 401, 404, etc..

    protected int $statusCode;
    // Внутренний строковый код ошибки для фронтенда
    protected string $errorCode;

    // Конструктор исключения
    public function __construct(string $message, int $statusCode = 400, string $errorCode = 'API_ERROR'){
        parent::__construct($message); // Текст сообщения, понятное для человек
        $this->statusCode = $statusCode; // HTTP - статус ответа
        $this->errorCode = $errorCode; // машинный код ошибки для фронтенда
    }
    // Получение HTTP-статуса ответа
    public function getStatusCode(): int{
        return $this->statusCode;
    }
    // Получение машинного кода ошибки
    public function getErrorCode(): string{
        return $this->errorCode;
    }
    // Метод render - автоматически вызывает при обработки исключения
    // Он перехватывает выброс данного исключения и превращает его в JSON-ответ,
    // избавляя контроллеры от необходимости писать try-catch и response()->json([])
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $this->getMessage(),
            'code' => $this->getErrorCode(),
        ], $this->getStatusCode());
    }
}
