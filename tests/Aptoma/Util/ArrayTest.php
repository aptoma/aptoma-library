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

    public function testDotStringsToDeepArray()
    {
        $dots = array(
            'ldap.server1.host' => 's0.foo.net',
            'ldap.server1.accountDomainName' => 'foo.net',
            'ldap.server1.accountDomainNameShort' => 'FOO',
            'ldap.server1.accountCanonicalForm' => '3',
            'ldap.server1.username' => '"CN=user1,DC=foo,DC=net"',
            'ldap.server1.password' => 'pass1',
            'ldap.server1.baseDn' => '"OU=Sales,DC=foo,DC=net"',
            'ldap.server1.bindRequiresDn' => 'true',
            'foo.monkey' => 'bar',
            'bar' => 'foo'
        );

        $expected = array(
            'ldap' => array(
                'server1' => array(
                    'host' => 's0.foo.net',
                    'accountDomainName' => 'foo.net',
                    'accountDomainNameShort' => 'FOO',
                    'accountCanonicalForm' => '3',
                    'username' => '"CN=user1,DC=foo,DC=net"',
                    'password' => 'pass1',
                    'baseDn' => '"OU=Sales,DC=foo,DC=net"',
                    'bindRequiresDn' => 'true'
                )
            ),
            'foo' => array(
                'monkey' => 'bar'
            ),
            'bar' => 'foo'
        );
        $res = Aptoma_Util_Array::dotStringsToDeepArray($dots);
        $this->assertEquals($res, $expected);
    }

    public function testConvertValuesToCorrectType()
    {
        $arr = array(
            'host' => 's0.foo.net',
            'bar' => 'foo',
            'number' => '3',
            'boolean' => 'true',
            'false' => 'false'
        );

        Aptoma_Util_Array::convertValuesToCorrectType($arr);
        $this->assertEquals('s0.foo.net', $arr['host']);
        $this->assertEquals('foo', $arr['bar']);
        $this->assertEquals(3, $arr['number']);
        $this->assertEquals(true, $arr['boolean']);
        $this->assertEquals(false, $arr['false']);
    }
}
