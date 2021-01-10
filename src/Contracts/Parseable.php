<?php
namespace atomita\Igo\Contracts;

interface Parseable
{
    public function parse(string $text): PieceIterable;
}
