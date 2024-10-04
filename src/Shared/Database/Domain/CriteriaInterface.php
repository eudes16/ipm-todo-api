<?php

declare(strict_types=1);

namespace App\Shared\Database\Domain;

use App\Shared\Database\Domain\CriteriaOperatorEnum;

/**
 * Interface CriteriaInterface - Represents the criteria interface.
 * @package App\Shared\Database\Domain
 * @param mixed $field
 * @param mixed | CriteriaInterface $value
 * @param CriteriaOperatorEnum $operator
 * @param mixed $condition
 * @param mixed $type
 */
interface CriteriaInterface
{

}