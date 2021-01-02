<?php
namespace atomita\Igo\Contracts;

interface Searchable
{
    public function search(string $text, int $start): ViterbiNodeIterable;
}
