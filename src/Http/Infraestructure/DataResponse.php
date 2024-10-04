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

    public function __construct($data, $code, $message = '') {
        $this->data = $data;
        $this->code = $code;
        $this->message = $message;
        $this->json();
    }

    /**
     * Send the response as JSON.
     * @return void
     */
    private function json() {
        header('Content-Type: application/json; charset=utf-8'); 

        echo json_encode([
            'data' => $this->data,
            'code' => $this->code,
            'message' => $this->message
        ]);
        return;
    }

    public static function notImplemented() {
        return new DataResponse(
            [
                "message" => "Not implemented"
            ],
            HttpCodes::BAD_REQUEST
        );
    }

    public static function notFound() {
        return new DataResponse(
            [
                "message" => "Not found"
            ],
            HttpCodes::NOT_FOUND
        );
    }
}