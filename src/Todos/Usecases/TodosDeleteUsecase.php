<?php

declare(strict_types=1);

namespace App\Todos\Usecases;

use App\Shared\Context;
use App\Shared\Domain\CrudRepositoryInterface;
use App\Shared\Domain\RepositoryInterface;
use App\Shared\Domain\UsecaseInterface;
use App\Shared\Exceptions\FieldNotFoundException;
use App\Shared\Exceptions\RecordNotFoundException;

class TodosDeleteUsecase implements UsecaseInterface
{
    public function __construct(
        private RepositoryInterface | CrudRepositoryInterface $repository,
        private Context $context,
        private $request
    ) {
        $this->validate();
    }

    public function validate(): void
    {

        if (!isset($this->request->data['id'])) {
            throw new FieldNotFoundException("Id is required");
        }

        return;
    }

    public function execute(): array
    {
        $result = [];
        try {
            [$findResult, $count]  = $this->repository->find(['id' => (int) $this->request->data['id']]);
            
            if (!isset($count) || $count === 0) {
                throw new RecordNotFoundException("Record not found");
            }

            $resultDelete = $this->repository->delete($this->request->data);

            if (!$resultDelete) {
                throw new RecordNotFoundException("Record not found");
            }

            return $findResult;
        } catch (\Throwable $th) {
            throw $th;
        }

        return $result;
    }
}