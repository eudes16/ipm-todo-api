<?php

declare(strict_types=1);

namespace App\Models;

use App\Shared\Database\Model;

class TodosModel extends Model
{
    protected string $tableName = 'todos';
    protected string $primaryKey = 'id';
    protected array $columns = [
        'id',
        'title',
        'description',
        'status',
        'due_date',
        'created_at',
        'updated_at',
    ];

    public function __construct($connection)
    {
        parent::__construct($connection);
    }
}
