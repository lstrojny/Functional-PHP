<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional;

use Functional\Exceptions\InvalidArgumentException;
use Traversable;

/**
 * @template K of array-key
 * @template V
 * @template R
 * @param iterable<K, V> $collection
 * @param callable(V, K, iterable<K, V>, R): R $callback
 * @param R $initial
 * @return R
 */
function reduce_left($collection, callable $callback, $initial = null)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    foreach ($collection as $index => $value) {
        $initial = $callback($value, $index, $collection, $initial);
    }

    return $initial;
}
