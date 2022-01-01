<?php
namespace atomita\Igo\Standard;

class Words
{
    public function __construct(
        $toIdPath,
        $datPath,
        $indicesPath,
        $infPath
    )
    {
        // toId
        $this->size = 0;
        $this->base = [];
        $this->check = [];
        $this->begs = [];
        $this->lens = [];
        $this->tail = [];

        // dat
        $this->data = [];

        // indices
        $this->indices = [];

        // inf
        $this->dataOffsets = [];
        $this->costs = [];
        $this->leftIds = [];
        $this->rightIds = [];
    }
}
