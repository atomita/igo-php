<?php
namespace atomita\Igo\Traits;

trait Bean
{
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }

    public function __toString()
    {
        return json_encode($this->__serialize(), JSON_UNESCAPED_UNICODE);
    }

    public function __serialize()
    {
        return get_object_vars($this);
    }
}
