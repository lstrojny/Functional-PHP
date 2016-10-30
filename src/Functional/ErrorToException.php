<?php
/**
 * Copyright (C) 2011-2016 by Lars Strojny <lstrojny@php.net>
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

use ErrorException;

/**
 * Takes a function and returns a new function that wraps the callback and rethrows PHP errors as exception
 *
 * @param callable $callback
 * @throws ErrorException Throws exception if PHP error happened
 * @return mixed
 */
function error_to_exception(callable $callback)
{
    return function (...$arguments) use ($callback) {

        error_clear_last();
        $errorLevel = error_reporting(0);

        $result = $callback(...$arguments);

        $error = error_get_last();
        error_reporting($errorLevel);

        if ($error) {
            throw new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
        }

        return $result;
    };
}
