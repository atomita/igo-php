<?php
namespace atomita\Igo\Contracts;

use atomita\Igo\Piece;

interface PieceIterable extends \Iterator
{
    public function current(): Piece;
}
