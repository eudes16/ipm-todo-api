<?php 

namespace App\Http\Domain;

use App\Http\Infraestructure\DataResponse;

interface CrudControllerInterface extends ControllerInterface
{
    public function create(): DataResponse;
    public function store(): DataResponse;
    public function show(): DataResponse;
    public function edit(): DataResponse;
    public function update(): DataResponse;
    public function destroy(): DataResponse;
}