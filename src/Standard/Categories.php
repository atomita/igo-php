<?php
namespace atomita\Igo\Standard;

use atomita\Igo\Category;

class Categories
{
    public function __construct($path)
    {

    }

    public function getCategory(string $char): Category
    {
        return new Category();
    }

    public function isCompatible(string $char, string $nextChar): bool
    {
        return false;
    }
}
