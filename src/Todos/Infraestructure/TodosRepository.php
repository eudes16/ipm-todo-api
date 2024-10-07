<?php

declare(strict_types=1);

namespace App\Todos\Infraestructure;

use App\Models\TodosModel;
use App\Shared\Context;
use App\Shared\Database\Criteria;
use App\Shared\Database\Domain\CriteriaOperatorEnum;
use App\Shared\Domain\RepositoryInterface;

class TodosRepository implements RepositoryInterface
{
    private Context $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function find(array $filters): array
    {
        $where = [];

        if (isset($filters['id_eq']) || isset($filters['id'])) {
            $value = $filters['id_eq'] ?? $filters['id'];
            $where = array_merge($where, (new Criteria('id', $value))->resolve());
        }

        if (isset($filters['title_eq']) || isset($filters['title'])) {
            $value = $filters['title_eq'] ?? $filters['title'];
            $where = array_merge($where, (new Criteria('title', $value))->resolve());
        }

        if (isset($filters['title_like'])) {
            $where = array_merge($where, (new Criteria('title', $filters['title_like'], CriteriaOperatorEnum::LIKE))->resolve());
        }

        if (isset($filters['description_like'])) {
            $where = array_merge($where, (new Criteria('description', $filters['description_like'], CriteriaOperatorEnum::LIKE))->resolve());
        }

        if (isset($filters['status_eq'])) {
            $where = array_merge($where, (new Criteria('status', $filters['status_eq']))->resolve());
        }

        if (isset($filters['status_not_eq'])) {
            $where = array_merge($where, (new Criteria('status', $filters['status_not_eq'], CriteriaOperatorEnum::NOT_EQUALS))->resolve());
        }

        if (isset($filters['status_in'])) {
            $where = array_merge($where, (new Criteria('status', $filters['status_in'], CriteriaOperatorEnum::IN))->resolve());
        }

        if (isset($filters['status_not_in'])) {
            $where = array_merge($where, (new Criteria('status', $filters['status_not_in'], CriteriaOperatorEnum::NOT_IN))->resolve());
        }

        if (isset($filters['created_at_gte'])) {
            $where = array_merge($where, (new Criteria('created_at', $filters['created_at_gte'], CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS))->resolve());
        }

        if (isset($filters['created_at_lte'])) {
            $where = array_merge($where, (new Criteria('created_at', $filters['created_at_lte'], CriteriaOperatorEnum::LESS_THAN_OR_EQUALS))->resolve());
        }

        if (isset($filters['updated_at_gte'])) {
            $where = array_merge($where, (new Criteria('updated_at', $filters['updated_at_gte'], CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS))->resolve());
        }

        if (isset($filters['updated_at_lte'])) {
            $where = array_merge($where, (new Criteria('updated_at', $filters['updated_at_lte'], CriteriaOperatorEnum::LESS_THAN_OR_EQUALS))->resolve());
        }

        if (isset($filters['due_date_gte'])) {
            $where = array_merge($where, (new Criteria('due_date', $filters['due_date_gte'], CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS))->resolve());
        }

        // TODO: Add more filters

        $todosModel = new TodosModel($this->context);
        $countResult = $todosModel->where($where)->count();

        $order = $this->resolveOrder();

        list($limit, $offset) = $this->resolvePagination();

        $result = $todosModel->select(["*"])
            ->where($where)
            ->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->get();

        return [$result, $countResult];
    }

    public function create(array $todo): array
    {
        $model = new TodosModel($this->context);
        $result = $model->insert($todo);
        return $result;
    }

    public function update(array $todo): array
    {
        $id = (int) $todo['id'];

        $model = new TodosModel($this->context);
        $result = $model->update($id, $todo);
        return $result;
    }

    public function delete(array $todo): bool
    {
        $id = (int) $todo['id'];

        $model = new TodosModel($this->context);
        $result = $model->delete($id);
        return $result;
    }

    private function resolvePagination(): array
    {

        $paginationData = $this->context->session['pagination'] ?? [];

        $page = isset($paginationData['page']) ? (int) $paginationData['page'] : 1;
        $limit = isset($paginationData['limit']) ? (int) $paginationData['limit'] : 0;

        $offset = ($page - 1) * $limit;

        return [$limit, $offset];
    }

    private function resolveOrder(): array
    {
        $parsedOrder = [];
        $orderData = $this->context->session['order'] ?? [];

        foreach ($orderData as $key => $value) {
            $order = explode('_', $value);
            $orientation = $order[1] ?? 'ASC';
            $parsedOrder[] = "{$order[0]} {$orientation}";
        }


        return $parsedOrder;
    }
}
