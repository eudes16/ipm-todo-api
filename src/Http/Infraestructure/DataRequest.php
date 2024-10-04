<?php 

namespace App\Http\Infraestructure;

/**
 * Class DataRequest - Represents the data of a request.
 * @package App\Http\Infraestructure
 */
class DataRequest {

    /**
     * @var string - Stores the HTTP method of the request.
     */
    public string $method;

    /**
     * @var string - Stores the path of the request.
     */
    public string $path;

    /**
     * @var array - Stores the data of the request.
     */
    public array $data;

    public function __construct(string $method, string $path, array $data) {
        $this->method = $method;
        $this->path = $path;
        $this->data = $data;
    }
}