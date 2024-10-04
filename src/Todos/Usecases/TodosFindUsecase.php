<?php

declare(strict_types=1);

namespace App\Todos\Usecases;

use App\Shared\Context;
use App\Shared\Domain\RepositoryInterface;
use App\Shared\Domain\UsecaseInterface;

class TodosFindUsecase implements UsecaseInterface
{

    private RepositoryInterface $repository;
    private Context $context;
    private $request;

    public function __construct(
        $repository,
        $context,
        $request
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
            $result = $this->repository->find($this->request->data);
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        }
        return $result;
    }
}
