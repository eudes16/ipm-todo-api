<?php

declare(strict_types=1);

namespace App\Todos\Usecases;

use App\Shared\Context;
use App\Shared\Domain\CrudRepositoryInterface;
use App\Shared\Domain\RepositoryInterface;
use App\Shared\Domain\UsecaseInterface;
use App\Shared\Exceptions\FieldNotFoundException;
use App\Shared\Exceptions\RecordAlreadExistsException;

class TodosCreateUsecase implements UsecaseInterface
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
        $data = $this->request->data;

        if (!isset($data['title'])) {
            throw new FieldNotFoundException("Title is required");
        }

        if (!isset($data['description'])) {
            throw new FieldNotFoundException("Description is required");
        }
        
        return;
    }

    public function execute(): array
    {
        $result = [];
        try {

            [$_, $count] = $this->repository->find(['title' => $this->request->data['title']]);

            if (isset($count) && $count > 0) {
                throw new RecordAlreadExistsException("Record already exists");
            }

            $result = $this->repository->create($this->request->data);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $result;
    }
}