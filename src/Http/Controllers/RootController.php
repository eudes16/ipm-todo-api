<?php

namespace App\Http\Controllers;

use App\Http\Constants\HttpCodes;
use App\Http\Domain\ControllerInterface;
use App\Http\Infraestructure\DataResponse;

class RootController implements ControllerInterface
{
    public function index($request, $context): DataResponse
    {
        $version = $context->app['version'];
        $name = $context->app['name'];

        return new DataResponse(
            [
                'name' => $name,
                'version' => $version,
            ],
            HttpCodes::OK
        );
    }

    public function signal()
    {
        echo 'Signal received!';
    }
}