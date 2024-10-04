<?php

declare(strict_types=1);

namespace App\Shared\Database;

use App\Shared\Database\Domain\CriteriaInterface;
use App\Shared\Database\Domain\CriteriaOperatorEnum;

class Criteria implements CriteriaInterface
{

    private $field;
    private $value;
    private $operator;

    public function __construct($field, $value, CriteriaOperatorEnum $operator = CriteriaOperatorEnum::EQUALS)
    {
        $this->field = $field;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function resolve(): array
    {

        $operator = $this->operator;
        $field = $this->field;

        $operatorValue = '';


        if ($operator == CriteriaOperatorEnum::EQUALS) {
            $operatorValue = CriteriaOperatorEnum::EQUALS->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT_EQUALS) {
            $operatorValue = CriteriaOperatorEnum::NOT_EQUALS->value;
        }

        if ($operator == CriteriaOperatorEnum::GREATER_THAN) {
            $operatorValue = CriteriaOperatorEnum::GREATER_THAN->value;
        }

        if ($operator == CriteriaOperatorEnum::LESS_THAN) {
            $operatorValue = CriteriaOperatorEnum::LESS_THAN->value;
        }

        if ($operator == CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS) {
            $operatorValue = CriteriaOperatorEnum::GREATER_THAN_OR_EQUALS->value;
        }

        if ($operator == CriteriaOperatorEnum::LESS_THAN_OR_EQUALS) {
            $operatorValue = CriteriaOperatorEnum::LESS_THAN_OR_EQUALS->value;
        }

        if ($operator == CriteriaOperatorEnum::LIKE) {
            $operatorValue = CriteriaOperatorEnum::LIKE->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT_LIKE) {
            $operatorValue = CriteriaOperatorEnum::NOT_LIKE->value;
        }

        if ($operator == CriteriaOperatorEnum::IN) {
            $operatorValue = CriteriaOperatorEnum::IN->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT_IN) {
            $operatorValue = CriteriaOperatorEnum::NOT_IN->value;
        }

        if ($operator == CriteriaOperatorEnum::IS_NULL) {
            $operatorValue = CriteriaOperatorEnum::IS_NULL->value;
        }

        if ($operator == CriteriaOperatorEnum::IS_NOT_NULL) {
            $operatorValue = CriteriaOperatorEnum::IS_NOT_NULL->value;
        }

        if ($operator == CriteriaOperatorEnum::BETWEEN) {
            $operatorValue = CriteriaOperatorEnum::BETWEEN->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT_BETWEEN) {
            $operatorValue = CriteriaOperatorEnum::NOT_BETWEEN->value;
        }

        if ($operator == CriteriaOperatorEnum::EXISTS) {
            $operatorValue = CriteriaOperatorEnum::EXISTS->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT_EXISTS) {
            $operatorValue = CriteriaOperatorEnum::NOT_EXISTS->value;
        }

        if ($operator == CriteriaOperatorEnum::AND) {
            $operatorValue = CriteriaOperatorEnum::AND->value;
        }

        if ($operator == CriteriaOperatorEnum::OR) {
            $operatorValue = CriteriaOperatorEnum::OR->value;
        }

        if ($operator == CriteriaOperatorEnum::NOT) {
            $operatorValue = CriteriaOperatorEnum::NOT->value;
        }

        if ($operatorValue == '') {
            return '';
        }


        if ($operator == CriteriaOperatorEnum::IS_NULL || $operator == CriteriaOperatorEnum::IS_NOT_NULL) {
            $criteria = "$field $operatorValue";
        } else {
            $criteria = "$field $operatorValue :$field";
        }

        return ["where" => $criteria, "value" => $this->value, "field" => $field];
    }
}
