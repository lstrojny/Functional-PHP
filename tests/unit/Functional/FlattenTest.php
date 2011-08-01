<?php
/**
 * Copyright (C) 2011 by David Soria Parra <dsp@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Functional;

class FlattenTest extends AbstractTestCase
{
    function setUp()
    {
        parent::setUp();
        $this->goodArray = array(1, 2, 3, array(4, 5, 6, array(7, 8, 9)), 10, array(11));
        $this->goodArray2 = array(1 => 1, "foo" => "2", 3 => "3", array("foo" => 5));
    }

    function test()
    {
        $this->assertSame(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11),
            flatten($this->goodArray));
        $this->assertSame(array(1, "2", "3", 5),
            flatten($this->goodArray2));
    }

    function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\flatten() expects parameter 1 to be array or instance of Traversable');
        flatten('invalidCollection', 'strlen');
    }
}
