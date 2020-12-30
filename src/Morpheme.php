<?php
namespace atomita\Igo;

class Morpheme
{
    use Traits\Bean;

    protected $surface;
    protected $feature;
    protected $start;

    public function __construct(string $surface, string $feature, int $start)
    {
        $this->surface = $surface;
        $this->feature = $feature;
        $this->start   = $start;
    }
}
