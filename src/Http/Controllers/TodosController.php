<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Constants\HttpCodes;
use App\Http\Domain\ControllerInterface;
use App\Http\Domain\CrudControllerInterface;
use App\Http\Infraestructure\DataRequest;
use App\Http\Infraestructure\DataResponse;
use App\Shared\Context;
use App\Todos\Infraestructure\TodosRepository;
use App\Todos\Usecases\TodosCreateUsecase;
use App\Todos\Usecases\TodosDeleteUsecase;
use App\Todos\Usecases\TodosFindUsecase;
use App\Todos\Usecases\TodosUpdateUsecase;

/**
 * Class TodoController - Represents the controller of the Todo entity.
 * @package App\Http\Controllers
 */
class TodosController implements CrudControllerInterface
{

    public function index(DataRequest $request, Context $context): DataResponse
    {

        try {
            $usecase = new TodosFindUsecase(new TodosRepository($context), $context, $request);
            $result = $usecase->execute();
            return new DataResponse(
                $result,
                HttpCodes::OK
            );
        } catch (\Throwable $th) {
            return new DataResponse(
                [
                    "message" => $th->getMessage()
                ],
                HttpCodes::BAD_REQUEST
            );
        }
    }

    public function find(DataRequest $request, Context $context): DataResponse
    {
        return DataResponse::notImplemented();
    }

    public function create(DataRequest $request, Context $context): DataResponse
    {
        try {
            $usecase = new TodosCreateUsecase(new TodosRepository($context), $context, $request);
            $result = $usecase->execute();
            return new DataResponse(
                $result,
                HttpCodes::CREATED
            );
        } catch (\Throwable $th) {
            return new DataResponse(
                null,
                HttpCodes::BAD_REQUEST,
                $th->getMessage()
            );
        }

        return DataResponse::notFound();
    }

    public function update(DataRequest $request, Context $context): DataResponse
    {
        try {
            $usecase = new TodosUpdateUsecase(new TodosRepository($context), $context, $request);
            $result = $usecase->execute();
            return new DataResponse(
                $result,
                HttpCodes::OK
            );
        } catch (\Throwable $th) {
            return new DataResponse(
                null,
                HttpCodes::BAD_REQUEST,
                $th->getMessage()
            );
        }
        return DataResponse::notFound();
    }

    public function edit(DataRequest $request, Context $context): DataResponse
    {
        return DataResponse::notImplemented();
    }


    public function delete(DataRequest $request, Context $context): DataResponse
    {
        try {
            $usecase = new TodosDeleteUsecase(new TodosRepository($context), $context, $request);
            $result = $usecase->execute();
            return new DataResponse(
                $result,
                HttpCodes::OK
            );
        } catch (\Throwable $th) {
            return new DataResponse(
                null,
                HttpCodes::BAD_REQUEST,
                $th->getMessage()
            );
        }
    }
}
