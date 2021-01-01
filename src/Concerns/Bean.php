<?php
namespace atomita\Igo\Concerns;

trait Bean
{
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toJson(int $flags = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->toArray(), $flags);
    }

    public function __toString(): string
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }
}
