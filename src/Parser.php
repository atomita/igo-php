<?php
namespace atomita\Igo;

class Parser implements Contracts\Parseable
{
    public function __construct(Contracts\Searchable $dictionary array $matrix)
    {
        $this->dictionary = $dictionary;
        $this->matrix     = $matrix;
    }

    /**
     * @param  string  $text
     * @return ViterbiNode
     */
    public function __invoke(string $text): Contracts\ViterbiNodeIterable
    {
        $len = mb_strlen($text);

        $viterbiTupleMatrix = array_fill(0, $len + 1, null);
        $viterbiTupleMatrix[0] = [new ViterbiTuple(null, new ViterbiNode(0, 0, 0, 0, 0, 0, false, ''), 0)];

        for ($i = 0; $i < $len; $i++) {
            if (!empty($viterbiTupleMatrix[$i])) {
                $prevs = $viterbiTupleMatrix[$i];

                // memory release
                unset($viterbiTupleMatrix[$i]);

                foreach ($this->dictionary->search($text, $i) as $viterbiNode) {
                    $end = $i + $viterbiNode->length;

                    if ($viterbiNode->isSpace) {
                        $viterbiTupleMatrix[$end] = $prevs;
                    } else {
                        [$prev, $cost] = $this->getLowestCostPrevAndCost($viterbiNode->rightId, ...$prevs);
                        $viterbiTupleMatrix[$end][] = new ViterbiTuple($prev, $viterbiNode, $cost);
                    }
                }
            }
        }

        [$last] = $this->getLowestCostPrevAndCost(0, ...$viterbiTupleMatrix[$len]);

        return new ViterbiNodeIterator(array_reverse(array_slice($last->toViterbiNodeArray(), -1)));
    }

    protected function getLowestCostPrevAndCost($rightId, ViterbiTuple ...$prevs)
    {
        $lowest = [];

        foreach ($prevs as $prev) {
            $cost = $prev->cost + $this->matrix[$prev->node->leftId][$rightId];
            if (empty($lowest) || $cost < $lowest[1]) {
                $lowest = [$prev, $cost];
            }
        }

        return $lowest;
    }
}
