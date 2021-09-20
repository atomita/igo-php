<?php
namespace atomita\Igo;

class Text
{
    protected $value;
    protected $length = null;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function length()
    {
        return $this->length ?? ($this->length = mb_strlen($this->value));
    }

    public function char(int $i): string
    {
        return mb_substr($this->value, $i, 1);
    }

    public function charCode(int $i): int
    {
        return hexdec(bin2hex(mb_convert_encoding($this->char($i), 'UCS-4')));
    }
}
