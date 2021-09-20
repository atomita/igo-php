<?php
namespace atomita\Igo;

class Dictionary implements Contracts\Searchable
{
    protected $words;
    protected $categories;

    protected $base;
    protected $indices;
    protected $begs;
    protected $lens;
    protected $check;
    protected $tail;

    public function __construct($words, $categories)
    {
        $this->words      = $words;
        $this->categories = $categories;
    }

    /**
     * @param  string  $text
     * @param  int     $start
     * preturn Contracts\PieceIterable
     */
    public function search(Text $text, int $start): Contracts\PieceIterable
    {
        return new PieceIterator(
            ...$this->searchCategories($text, $start, ...$this->searchWords($text, $start))
        );
    }

    protected function searchWords(Text $text, int $start): \Generator
    {
        $node = $this->base[0];

        $length = $text->length();

        for ($i = $start; $i < $length; $i++) {
            $code = $text->charCode($i);

            if ($this->check[$node] === 0) {
                // match
                yield from $this->createPiecesByIndex($start, $i - $start + 1, $this->base[$node]);
                if ($code === 0) {
                    return;
                }
            }


            $idx  = $node + $code;
            $node = $this->base[$idx];

            if ($this->check[$idx] !== $code) {
                // not match
                return;
            }

            if ($node < 0) {
                // match depending on continued string
                yield from $this->createPiecesByTail($text, $node, $start, $i);
                return;
            }
        }
    }

    protected function searchCategories(Text $text, int $start, Piece ...$pieces): \Generator
    {
        // todo
        yield from $pieces;
    }

    protected function createPiecesByIndex($start, $length, $base, ?Category $category = null): \Generator
    {
        $trieId = $base * -1 - 1;
        $end    = $this->indices[$trieId + 1];

        for ($i = $this->indices[$trieId]; $i < $end; $i++) {
            yield new Piece(
                $i,
                $start,
                $length,
                $this->costs[$i],
                $this->leftIds[$i],
                $this->rightIds[$i],
                false,
                function () use ($i) {
                    return mb_substr($this->data, $this->dataOffsets[$i], $this->dataOffsets[$i + 1]);
                },
                $category
            );
        }
    }

    protected function createPiecesByTail($text, $node, $start, $offset): \Generator
    {
        $id  = $node * -1 - 1;
        $beg = $this->begs[$id];
        $len = $this->lens[$id];

        if ($text->length() - $offset <= $len) {
            return;
        }

        if (mb_substr($this->tail, $beg, $len) == mb_substr((string)$text, $offset + 1, $len)) {
            yield from $this->createPiecesByIndex($start, $offset - $start + 1 + $len, $node);
        }
    }
}
