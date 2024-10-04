<?php 

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Context;

interface UsecaseInterface
{
    public function validate(): void;
    public function execute(): array;
}