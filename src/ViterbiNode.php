<?php
namespace atomita\Igo;

class ViterbiNode
{
    use Concerns\Bean;

    protected $cost;
    protected $isSpace;
    protected $leftId;
    protected $length;
    protected $rightId;
    protected $start;
    protected $wordId;
    protected $data;

    public function __construct(
        int $wordId,
        int $start,
        int $length,
        int $cost,
        int $leftId,
        int $rightId,
        bool $isSpace,
        string $data
    ) {
        $this->cost    = $cost;
        $this->data    = $data;
        $this->isSpace = $isSpace;
        $this->leftId  = $leftId;
        $this->length  = $length;
        $this->rightId = $rightId;
        $this->start   = $start;
        $this->wordId  = $wordId;
    }
}
