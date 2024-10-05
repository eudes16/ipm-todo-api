<?php 

declare(strict_types=1);

namespace App\Shared\Domain;

interface CrudRepositoryInterface extends RepositoryInterface
{
    public function create(array $data): array;
    public function update(array $data): array;
    public function delete(array $data): bool;
}