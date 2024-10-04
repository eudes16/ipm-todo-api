<?php

declare(strict_types=1);

namespace App\Todos\Usecases;

use App\Shared\Domain\UsecaseInterface;
use App\Shared\Exceptions\FieldNotFoundException;

class TodosCreateUsecase implements UsecaseInterface
{
    public function __construct(
        private $repository,
        private $context,
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
            $result = $this->repository->create($this->request->data);
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
        }
        return $result;
    }
}
{}