<?php

namespace Scheb\Tombstone\Test\Formatter;

use Scheb\Tombstone\Test\TestCase;
use Scheb\Tombstone\Formatter\JsonFormatter;
use Scheb\Tombstone\Test\Fixtures\VampireFixture;

class JsonFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function format_vampireGiven_returnFormattedString()
    {
        $vampire = VampireFixture::getVampire();
        $formatter = new JsonFormatter();
        $returnValue = $formatter->format($vampire);
        $expectedLog = '{"tombstoneDate":"2014-01-01","author":"author","label":"label","file":"file","line":"line","method":"method","invocationDate":"2015-01-01","invoker":"invoker"}';
        $this->assertEquals($expectedLog."\n", $returnValue);
    }
}
