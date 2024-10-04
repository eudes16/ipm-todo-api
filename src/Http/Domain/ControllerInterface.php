<?php

declare(strict_types=1);

namespace App\Http\Domain;

use App\Shared\Context;
use App\Http\Infraestructure\DataRequest;
use App\Http\Infraestructure\DataResponse;

interface ControllerInterface
{
    public function index(DataRequest $request, Context $context): DataResponse;
}