<?php
class Aptoma_Util_ArrayTest extends PHPUnit_Framework_TestCase
{
    public function testSortObjects()
    {
        $a = new stdClass();
        $a->name = 'a';
        $b = new stdClass();
        $b->name = 'b';
        $c = new stdClass();
        $c->name = 'c';
        $d = new stdClass();
        $d->name = 'd';

        $list = array($d, $b, $c, $a);
        $sorted = Aptoma_Util_Array::sortObjects($list, 'name');

        $this->assertEquals($sorted[0]->name, 'a');
        $this->assertEquals($sorted[1]->name, 'b');
        $this->assertEquals($sorted[2]->name, 'c');
        $this->assertEquals($sorted[3]->name, 'd');

        $reverse = Aptoma_Util_Array::sortObjects($sorted, 'name', true);
        $this->assertEquals($reverse[0]->name, 'd');
        $this->assertEquals($reverse[1]->name, 'c');
        $this->assertEquals($reverse[2]->name, 'b');
        $this->assertEquals($reverse[3]->name, 'a');
    }
}
