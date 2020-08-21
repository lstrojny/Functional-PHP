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
use WeakReference;

/**
 * Memoizes callbacks and returns their value instead of calling them
 *
 * @param callable|null $callback Callable closure or function. Pass null to reset memory
 * @param array $arguments Arguments
 * @param array|string $key Optional memoize key to override the auto calculated hash
 * @return mixed
 */
function memoize(callable $callback = null, $arguments = [], $key = null)
{
    static $storage = [];
    /** @var object[]|WeakReference[] $objectRefs */
    static $objectRefs = [];

    if ($callback === null) {
        $storage = [];

        return null;
    }

    if (\is_callable($arguments)) {
        $key = $arguments;
        $arguments = [];
    } else {
        InvalidArgumentException::assertCollection($arguments, __FUNCTION__, 2);
    }

    static $keyGenerator = null;
    if (!$keyGenerator) {
        $keyGenerator = static function ($value) use (&$keyGenerator, &$objectRefs) {
            $type = \gettype($value);
            if ($type === 'array') {
                $key = \implode(':', map($value, $keyGenerator));
            } elseif ($type === 'object') {
                $hash = \spl_object_hash($value);
                /**
                 * spl_object_hash() will return the same hash twice in a single request if an object goes out of scope
                 * and is destructed.
                 */
                if (PHP_VERSION_ID >= 70400) {
                    /**
                     * For PHP >=7.4, we keep a weak reference to the relevant object that we use for hashing. Once the
                     * object gets out of scope, the weak ref will no longer return the object, that’s how we know we
                     * have a collision and increment a version in the collisions array.
                     */
                    /** @var int[] $collisions */
                    static $collisions = [];

                    if (isset($objectRefs[$hash])) {
                        if ($objectRefs[$hash]->get() === null) {
                            $collisions[$hash] = ($collisions[$hash] ?? 0) + 1;
                            $objectRefs[$hash] = WeakReference::create($value);
                        }
                    } else {
                        $objectRefs[$hash] = WeakReference::create($value);
                    }

                    $key = \get_class($value) . ':' . $hash . ':' . ($collisions[$hash] ?? 0);
                } else {
                    /**
                     * For PHP < 7.4 we keep a static reference to the object so that cannot accidentally go out of
                     * scope and mess with the object hashes
                     */
                    $objectRefs[$hash] = $value;
                    $key = \get_class($value) . ':' . \spl_object_hash($value);
                }
            } else {
                $key = (string) $value;
            }

            return $key;
        };
    }

    if ($key === null) {
        $key = $keyGenerator(\array_merge([$callback], $arguments));
    } elseif (\is_callable($key)) {
        $key = $keyGenerator($key());
    } else {
        $key = $keyGenerator($key);
    }

    if (!isset($storage[$key]) && !\array_key_exists($key, $storage)) {
        $storage[$key] = $callback(...$arguments);
    }

    return $storage[$key];
}
