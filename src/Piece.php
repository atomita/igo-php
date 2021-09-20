<?php
namespace atomita\Igo;

class Piece
{
    use Concerns\Bean;

    protected $category;
    protected $cost;
    protected $data;
    protected $isSpace;
    protected $leftId;
    protected $length;
    protected $rightId;
    protected $start;
    protected $wordId;

    public function __construct(
        int $wordId,
        int $start,
        int $length,
        int $cost,
        int $leftId,
        int $rightId,
        bool $isSpace,
        ?callable $data = null,
        ?Category $category = null
    ) {
        $this->category = $category;
        $this->cost     = $cost;
        $this->data     = $data ?? [$this, 'returnEmptyString'];
        $this->isSpace  = $isSpace;
        $this->leftId   = $leftId;
        $this->length   = $length;
        $this->rightId  = $rightId;
        $this->start    = $start;
        $this->wordId   = $wordId;
    }

    public function returnEmptyString(): string
    {
        return '';
    }
}
