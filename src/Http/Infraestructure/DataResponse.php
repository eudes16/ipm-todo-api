<?php 

declare(strict_types=1);

namespace App\Http\Infraestructure;

use App\Http\Constants\HttpCodes;

/**
 * Class DataResponse - Represents the data of a response.
 * @package App\Http\Infraestructure
 */
class DataResponse {
    /**
     * @var mixed - Stores the data of the response.
     */
    public $data;

    /**
     * @var int - Stores the HTTP code of the response.
     */
    public int $code;

    /**
     * @var string - Stores the message of the response.
     */
    public string $message;

    public $pagination;

    public function __construct($data, $code, $message = '', $pagination = null) {
        $this->data = $data;
        $this->code = $code;
        $this->message = $message;
        $this->pagination = $pagination;
        $this->json();
    }

    /**
     * Send the response as JSON.
     * @return void
     */
    private function json() {
        header('Content-Type: application/json; charset=utf-8'); 
        http_response_code($this->code);
        $response = [
            'data' => $this->data,
            'code' => $this->code,
            'message' => $this->message,
        ];

        if ($this->pagination) {
            $response['pagination'] = $this->pagination;
        }

        echo json_encode($response);
        return;
    }

    public static function notImplemented() {
        http_response_code(HttpCodes::BAD_REQUEST);
        return new DataResponse(
            null,
            HttpCodes::BAD_REQUEST,
            "Not implemented"
        );
    }

    public static function notFound() {
        http_response_code(HttpCodes::NOT_FOUND);
        return new DataResponse(
            null,
            HttpCodes::NOT_FOUND,
            "Not found"
        );
    }

    public static function badRequest() {
        http_response_code(HttpCodes::BAD_REQUEST);
        return new DataResponse(
            null,
            HttpCodes::BAD_REQUEST,
            "Bad request"
        );
    }

    public static function unauthorized() {
        http_response_code(HttpCodes::UNAUTHORIZED);
        return new DataResponse(
            null,
            HttpCodes::UNAUTHORIZED,
            "Unauthorized"
        );
    }

    public static function forbidden() {
        http_response_code(HttpCodes::FORBIDDEN);
        return new DataResponse(
            null,
            HttpCodes::FORBIDDEN,
            "Forbidden"
        );
    }

    public static function internalServerError() {
        http_response_code(HttpCodes::INTERNAL_SERVER_ERROR);
        return new DataResponse(
            null,
            HttpCodes::INTERNAL_SERVER_ERROR,
            "Internal server error"
        );
    }
}