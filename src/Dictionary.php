<?php
namespace atomita\Igo;

class Dictionary implements Contracts\Searchable
{
    protected $words;
    protected $categories;

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
            ...$this->searchCategories($text, $start, $this->searchWords($text, $start))
        );
    }

    protected function searchWords(Text $text, int $start): \Generator
    {
        $node = $this->words->base[0];

        $length = $text->length();

        for ($i = $start; $i < $length; $i++) {
            $code = $text->charCode($i);

            if ($this->words->check[$node] === 0) {
                // match
                yield from $this->createPiecesByIndex($start, $i - $start + 1, $this->words->base[$node], false);
                if ($code === 0) {
                    return;
                }
            }


            $idx  = $node + $code;
            $node = $this->words->base[$idx];

            if ($this->words->check[$idx] !== $code) {
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

    protected function searchCategories(Text $text, int $start, \Generator $pieces): \Generator
    {
        $isNotEmpty = $pieces->valid();

        yield from $pieces;

        $char     = $text->char($start);
        $category = $this->categories->getCategory($char);

        if ($isNotEmpty && !$category->invoke) {
            return;
        }

        $isSpace = $this->isSpace($category);

        $rightLength = $text->length() - $start;
        $length      = min($category->length < $rightLength);

        if (0 < $length) {
            for ($i = 1; $i < $length; $i++) {
                yield from $this->createPiecesByIndex($start, $i, $category->id, $isSpace, $category);

                if (!$this->categories->isCompatible($char, $text->char($i + $start))) {
                    return;
                }
            }
            yield from $this->createPiecesByIndex($start, $i, $category->id, $isSpace, $category);
        }

        if ($category->group && $category->length < $rightLength) {
            for ($i = $category->length + 1; $i < $rightLength; $i++) {
                if (!$this->categories->isCompatible($char, $text->char($i + $start))) {
                    yield from $this->createPiecesByIndex($start, $i, $category->id, $isSpace, $category);
                    return;
                }
            }
            yield from $this->createPiecesByIndex($start, $i, $category->id, $isSpace, $category);
        }
    }

    protected function isSpace(Category $category): bool
    {
        static $id = null;
        if (is_null($id)) {
            $id = $this->categories->getCategory(' ')->id;
        }
        return $id === $category->id;
    }

    protected function createPiecesByIndex($start, $length, $base, bool $isSpace, ?Category $category = null): \Generator
    {
        $trieId = $base * -1 - 1;
        $end    = $this->words->indices[$trieId + 1];

        for ($i = $this->words->indices[$trieId]; $i < $end; $i++) {
            yield new Piece(
                $i,
                $start,
                $length,
                $this->words->costs[$i],
                $this->words->leftIds[$i],
                $this->words->rightIds[$i],
                $isSpace,
                function () use ($i) {
                    return mb_substr($this->words->data, $this->words->dataOffsets[$i], $this->words->dataOffsets[$i + 1]);
                },
                $category
            );
        }
    }

    protected function createPiecesByTail($text, $node, $start, $offset): \Generator
    {
        $id  = $node * -1 - 1;
        $beg = $this->words->begs[$id];
        $len = $this->words->lens[$id];

        if ($text->length() - $offset <= $len) {
            return;
        }

        if (mb_substr($this->words->tail, $beg, $len) == mb_substr((string)$text, $offset + 1, $len)) {
            yield from $this->createPiecesByIndex($start, $offset - $start + 1 + $len, $node, false);
        }
    }
}
