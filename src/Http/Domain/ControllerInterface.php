<?php

namespace App\Http\Domain;

use App\Commons\Context;
use App\Http\Infraestructure\DataRequest;
use App\Http\Infraestructure\DataResponse;

interface ControllerInterface
{
    public function index(DataRequest $request, Context $context): DataResponse;
}