<?php
namespace atomita\Igo;

class Parser implements Contracts\Parseable
{
    protected $dictionary;
    protected $costCalculator;

    public function __construct(Contracts\Searchable $dictionary, Contracts\CostCalculatable $costCalculator)
    {
        $this->dictionary     = $dictionary;
        $this->costCalculator = $costCalculator;
    }

    /**
     * @param  string  $text
     * @return Contracts\PieceIterable
     */
    public function parse(string $text): Contracts\PieceIterable
    {
        $text = new Text($text);

        $len = $text->length();

        $viterbiLattice    = array_fill(0, $len + 1, null);
        $viterbiLattice[0] = [new ViterbiTuple(null, new Piece(0, 0, 0, 0, 0, 0, false), 0)];

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
                        [$prev, $cost]          = $this->getLowestCostPrevAndCost($piece, ...$prevs);
                        $viterbiLattice[$end][] = new ViterbiTuple($prev, $piece, $cost);
                    }
                }
            }
        }

        [$last] = $this->getLowestCostPrevAndCost(
            new Piece(0, 0, 0, 0, 0, 0, false),
            ...$viterbiLattice[$len]
        );

        return new PieceIterator(
            array_map(
                function ($v) {
                    return $v->node;
                },
                array_reverse(array_slice($last->toArrayByReference(), -1))
            )
        );
    }

    protected function getLowestCostPrevAndCost(Piece $piece, ViterbiTuple ...$prevs): array
    {
        $lowest = [];

        foreach ($prevs as $prev) {
            $cost = $prev->cost + $this->costCalculator->cost($prev->node, $piece);
            if (empty($lowest) || $cost < $lowest[1]) {
                $lowest = [$prev, $cost];
            }
        }

        return $lowest;
    }
}
