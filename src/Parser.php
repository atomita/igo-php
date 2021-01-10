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
     * @return Contracts\PieceIterable
     */
    public function parse(string $text): Contracts\PieceIterable
    {
        $len = mb_strlen($text);

        $viterbiLattice = array_fill(0, $len + 1, null);
        $viterbiLattice[0] = [new ViterbiTuple(null, new Piece(0, 0, 0, 0, 0, 0, false, ''), 0)];

        for ($i = 0; $i < $len; $i++) {
            if (!empty($viterbiLattice[$i])) {
                $prevs = $viterbiLattice[$i];

                // Expect memory to be released
                unset($viterbiLattice[$i]);

                foreach ($this->dictionary->search($text, $i) as $piece) {
                    $end = $i + $piece->length;

                    if ($piece->isSpace) {
                        $viterbiLattice[$end] = $prevs;
                    } else {
                        [$prev, $cost] = $this->getLowestCostPrevAndCost($piece->rightId, ...$prevs);
                        $viterbiLattice[$end][] = new ViterbiTuple($prev, $piece, $cost);
                    }
                }
            }
        }

        [$last] = $this->getLowestCostPrevAndCost(0, ...$viterbiLattice[$len]);

        return new PieceIterator(
            array_mep(
                function ($v) { return $v->node; },
                array_reverse(array_slice($last->toArrayByReference(), -1))
            )
        );
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
