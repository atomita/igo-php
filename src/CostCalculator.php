<?php
namespace atomita\Igo;

class CostCalculator implements Contracts\CostCalculatable
{
    protected $matrix;

    public function __construct(array $matrix)
    {
        $this->matrix = $matrix;
    }

    public function cost(Piece $left, Piece $right): int
    {
        return $this->matrix[$left->leftId][$right->rightId];
    }
}
