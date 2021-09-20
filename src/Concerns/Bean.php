<?php
namespace atomita\Igo\Concerns;

trait Bean
{
    public static function newInstance(array $parameters): static
    {
        $class = new \ReflectionClass(static::class);

        $args = [];

        foreach ($class->getConstructor()->getParameters() as $i => $param) {
            if (isset($parameters[$i])) {
                $args[$i] = $parameters[$i];
            } else {
                $name = $param->getName();
                if (isset($parameters[$name])) {
                    $args[$i] = $parameters[$name];
                }
            }
        }

        return $class->newInstanceArgs($args);
    }

    public function copy(array $parameters = []): static
    {
        return static::newInstance($parameters + $this->toArray());
    }

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
