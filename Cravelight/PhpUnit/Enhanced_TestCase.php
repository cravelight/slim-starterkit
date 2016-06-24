<?php

namespace Cravelight\PhpUnit;

use PHPUnit_Framework_TestCase;

class Enhanced_TestCase extends PHPUnit_Framework_TestCase
{

    public function assertIsStringOfLength($value, int $expectedLength)
    {
        $this->assertNotNull($value);
        $this->assertTrue(is_string($value));
        $this->assertEquals($expectedLength, strlen($value));
    }


}
