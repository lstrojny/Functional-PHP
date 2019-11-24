<?php

/**
 * Copyright (C) 2011-2017 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Functional\Tests;

use Functional\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;

use function Functional\repeat;

class Repeated
{
    public function foo()
    {
    }
}

class RepeatTest extends AbstractTestCase
{
    /** @var Repeated|MockObject */
    private $repeated;

    public function setUp()
    {
        parent::setUp();
        $this->repeated = $this->createMock(Repeated::class);
    }

    public function test()
    {
        $this->repeated
            ->expects($this->exactly(10))
            ->method('foo');

        repeat([$this->repeated, 'foo'])(10);
    }

    public function testNegativeRepeatedTimes()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Functional\{closure}() expects parameter 1 to be positive integer, negative integer given'
        );

        repeat([$this->repeated, 'foo'])(-1);
    }
}
