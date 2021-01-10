<?php
namespace atomita\Igo\Contracts;

interface Parseable
{
    public function __invoke(string $text): PieceIterable;
}
