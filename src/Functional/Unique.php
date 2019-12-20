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
 * Returns an array of unique elements
 *
 * @psalm-pure
 *
 * @template TKey as array-key
 * @template TValue
 *
 * @psalm-param iterable<TKey, TValue> $collection
 * @psalm-param null|callable(TValue, TKey, iterable<TKey, TValue>):mixed $callback
 * @psalm-return array<TKey, TValue>
 *
 * @param Traversable|array $collection
 * @param null|callable $callback
 * @param bool $strict
 * @return array
 */
function unique($collection, callable $callback = null, $strict = true)
{
    InvalidArgumentException::assertCollection($collection, __FUNCTION__, 1);

    $indexes = [];
    $aggregation = [];
    foreach ($collection as $key => $element) {
        if ($callback) {
            $index = $callback($element, $key, $collection);
        } else {
            $index = $element;
        }

        if (!\in_array($index, $indexes, $strict)) {
            $aggregation[$key] = $element;

            $indexes[] = $index;
        }
    }

    return $aggregation;
}
