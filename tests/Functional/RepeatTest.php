<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2021 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use Functional\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\repeat;

class Repeated
{
    public function foo(): void
    {
    }
}

class RepeatTest extends AbstractTestCase
{
    /** @var Repeated|MockObject */
    private $repeated;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repeated = $this->createMock(Repeated::class);
    }

    public function test(): void
    {
        $this->repeated
            ->expects(self::exactly(10))
            ->method('foo');

        repeat([$this->repeated, 'foo'])(10);
    }

    public function testNegativeRepeatedTimes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // See https://3v4l.org/Ms79G for message formats
        // See https://regex101.com/r/hTvW3o/1 for regex setup
        $this->expectExceptionMessageMatches(
            '/(Functional\\\\{closure}' // PHP < 8.4
                . '|{closure:Functional\\\\repeat\(\):[0-9]+})' // PHP 8.4+
                . '\(\) expects parameter 1 to be positive integer, negative integer given/'
        );

        repeat([$this->repeated, 'foo'])(-1);
    }
}
