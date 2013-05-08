<?php
class Aptoma_Util_StringTest extends PHPUnit_Framework_TestCase
{
    public function testUnderscoreToCamelCase()
    {
        $this->assertEquals('fooBar', Aptoma_Util_String::underscoreToCamelCase('foo_bar'));
        $this->assertEquals('fooBarMojoFunCar', Aptoma_Util_String::underscoreToCamelCase('foo_bar_mojo_fun_car'));
        $this->assertEquals('__FooBar', Aptoma_Util_String::underscoreToCamelCase('___foo_bar'));
    }
}
