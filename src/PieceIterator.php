<?php
namespace atomita\Igo;

class PieceIterator extends \IteratorIterator implements Contracts\PieceIterable
{
    public function __constract(Piece ...$pieces)
    {
        parent::__construct((function () use ($pieces) {
            yield from $pieces;
        })());
    }

    public function current(): Piece
    {
        return parent::current();
    }
}
