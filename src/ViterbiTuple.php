<?php
namespace atomita\Igo;

class ViterbiTuple
{
    use Concerns\Bean;

    protected $ref;
    protected $node;
    protected $cost;

    public function __construct(?ViterbiTuple $ref, ViterbiNode $node, int $cost)
    {
        $this->cost = $cost;
        $this->node = $node;
        $this->ref  = $ref;
    }

    public function toViterbiNodeArray()
    {
        return iterator_to_array((function () {
            yield $this->node;
            
            while ($ref = $this->ref) {
                yield $ref->node;
            }
        })());
    }
}
