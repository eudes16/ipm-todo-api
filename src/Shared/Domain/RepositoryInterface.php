<?php 

declare(strict_types=1);

namespace App\Shared\Domain;

interface RepositoryInterface
{
    public function find(array $filters): array;
}