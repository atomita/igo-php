<?php
namespace atomita\Igo\Contracts;

use atomita\Igo\Text;

interface Searchable
{
    public function search(Text $text, int $start): PieceIterable;
}
