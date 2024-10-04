<?php

declare(strict_types=1);

namespace App\Todos\Infraestructure;

use App\Models\TodosModel;
use App\Shared\Database\Criteria;
use App\Shared\Database\Domain\CriteriaOperatorEnum;
use App\Shared\Domain\RepositoryInterface;

class TodosRepository implements RepositoryInterface
{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function find(array $filters): array
    {
        $where = [];

        if ($filters['id_eq'] || $filters['id']) {
            $where = array_merge($where, (new Criteria('id', $filters['id']))->resolve());
        }

        if ($filters['title_like']) {
            $where = array_merge($where, (new Criteria('title', $filters['title'], CriteriaOperatorEnum::LIKE))->resolve());
        }

        if ($filters['description_like']) {
            $where = array_merge($where, (new Criteria('description', $filters['description'], CriteriaOperatorEnum::LIKE))->resolve());
        }

        if ($filters['status_eq']) {
            $where = array_merge($where, (new Criteria('status', $filters['status']))->resolve());
        }

        if ($filters['created_at_gte']) {
            $where = array_merge($where, (new Criteria('created_at', $filters['created_at_gte'], CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS))->resolve());
        }

        if ($filters['created_at_lte']) {
            $where = array_merge($where, (new Criteria('created_at', $filters['created_at_lte'], CriteriaOperatorEnum::LESS_THAN_OR_EQUALS))->resolve());
        }

        if ($filters['updated_at_gte']) {
            $where = array_merge($where, (new Criteria('updated_at', $filters['updated_at_gte'], CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS))->resolve());
        }

        if ($filters['updated_at_lte']) {
            $where = array_merge($where, (new Criteria('updated_at', $filters['updated_at_lte'], CriteriaOperatorEnum::LESS_THAN_OR_EQUALS))->resolve());
        }

        $todosModel = new TodosModel($this->connection);
        $result = $todosModel->select(["*"])->where($where)->get();
        return $result;
    }

    public function create(array $todo): bool 
    {
        $model = new TodosModel($this->connection);
        $result = $model->insert($todo);


        if ($result) {
            $model->where([
                
            ])->get();
        }

        return $result; 
    }
}
