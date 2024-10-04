<?php 

declare(strict_types=1);

namespace App\Http\Domain;

use App\Http\Infraestructure\DataRequest;
use App\Http\Infraestructure\DataResponse;
use App\Shared\Context;

interface CrudControllerInterface extends ControllerInterface
{
    public function find(DataRequest $request, Context $context): DataResponse;
    public function create(DataRequest $request, Context $context): DataResponse;
    public function update(DataRequest $request, Context $context): DataResponse;
    public function edit(DataRequest $request, Context $context): DataResponse;
    public function delete(DataRequest $request, Context $context): DataResponse;
}