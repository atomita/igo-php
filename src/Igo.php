<?php
namespace atomita\Igo;

class Igo
{
    protected $parser;

    public function __construct(Contracts\Searchable $dictionary, Contracts\CostCalculatable $costCalculator)
    {
        $this->parser = new Parser($dictionary, $costCalculator);
    }

    /**
     * @return \Generator<Morpheme>
     */
    public function parse(string $text): \Generator
    {
        foreach ($this->parser->parse($text) as $piece) {
            yield new Morpheme(
                mb_substr($text, $piece->start, $piece->length),
                ($piece->data)(),
                $piece->start
            );
        }
    }

    /**
     * @return \Generator<string>
     */
    public function split(string $text): \Generator
    {
        foreach ($this->parser->parse($text) as $piece) {
            yield mb_substr($text, $piece->start, $piece->length);
        }
    }
}
