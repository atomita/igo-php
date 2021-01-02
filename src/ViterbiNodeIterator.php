<?php
namespace atomita\Igo;

class ViterbiNodeIterator extends \IteratorIterator implements Contracts\ViterbiNodeIterable
{
    public function __constract(ViterbiNode ...$viterbiNodes)
    {
        parent::__construct((function () use ($viterbiNodes) {
            yield from $viterbiNodes;
        })());
    }

    public function current(): ViterbiNode
    {
        return parent::current();
    }
}
