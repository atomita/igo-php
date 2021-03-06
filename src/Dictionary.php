<?php
namespace atomita\Igo;

class Dictionary implements Contracts\Searchable
{
    public function __construct($words, $categories)
    {
        $this->words      = $words;
        $this->categories = $categories;
    }

    /**
     * @param  string  $text
     * @param  int     $start
     * preturn Contracts\ViterbiNodeIterable
     */
    public function search(string $text, int $start): Contracts\ViterbiNodeIterable
    {
        // todo
        // wordsから探し、なければcategoriesから探す
        return new ViterbiNodeIterator(...[]);
    }
}
