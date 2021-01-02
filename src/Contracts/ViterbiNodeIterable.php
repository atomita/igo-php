<?php
namespace atomita\Igo\Contracts;

use atomita\Igo\ViterbiNode;

interface ViterbiNodeIterable extends \Iterator
{
    public function current(): ViterbiNode;
}
