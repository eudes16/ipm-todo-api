<?php

declare(strict_types=1);

namespace App\Shared\Database\Domain;

enum QueryDebugLevelEnum: int {
    case NONE = 0;
    case SQL = 1;
    case ALL = 2;
}