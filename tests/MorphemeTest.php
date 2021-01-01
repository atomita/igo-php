<?php
namespace Tests;

use atomita\Igo;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\isJson;

class MorphemeTest extends TestCase
{
    /**
     * @testWith ["foo","bar",1]
     */
    public function testCanGetProperty($surface, $feature, $start)
    {
        $morpheme = new Igo\Morpheme($surface, $feature, $start);

        assertEquals($surface, $morpheme->surface);
        assertEquals($feature, $morpheme->feature);
        assertEquals($start, $morpheme->start);

        assertThat($morpheme->toJson(), isJson());
    }

    /**
     * @testWith ["foo","bar",1]
     */
    public function testCanCreateInstanceFromArray($surface, $feature, $start)
    {
        $morpheme = Igo\Morpheme::newInstance(['surface' => $surface, 'feature' => $feature, 'start' => $start]);

        assertEquals($surface, $morpheme->surface);
        assertEquals($feature, $morpheme->feature);
        assertEquals($start, $morpheme->start);
    }
}
