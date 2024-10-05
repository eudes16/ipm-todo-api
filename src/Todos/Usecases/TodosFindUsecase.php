<?php

declare(strict_types=1);

namespace App\Todos\Usecases;

use App\Shared\Context;
use App\Shared\Domain\CrudRepositoryInterface;
use App\Shared\Domain\RepositoryInterface;
use App\Shared\Domain\UsecaseInterface;

class TodosFindUsecase implements UsecaseInterface
{

    public function __construct(
        private RepositoryInterface | CrudRepositoryInterface $repository,
        private Context $context,
        private $request
    ) {
        $this->repository = $repository;
        $this->context = $context;
        $this->request = $request;
    }

    public function validate(): void
    {
        return;
    }

    public function execute(): array
    {
        $result = [];
        try {
            [$result, $count] = $this->repository->find($this->request->data);

            
        } catch (\Throwable $th) {
            throw $th;
        }
        return $result;
    }
}
