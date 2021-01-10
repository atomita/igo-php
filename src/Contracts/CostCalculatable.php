<?php
namespace atomita\Igo\Contracts;

use atomita\Igo\Piece;

interface CostCalculatable
{
    public function cost(Piece $left, Piece $right): int;
}
