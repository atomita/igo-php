<?php
namespace atomita\Igo;

class Category
{
    use Concerns\Bean;

    protected $id;
    protected $length;
    protected $invoke;
    protected $group;

    public function __construct(int $id, int $length, bool $invoke, int $group)
    {
        $this->id     = $id;
        $this->length = $length;
        $this->invoke = $invoke;
        $this->group  = $group;
    }
}
