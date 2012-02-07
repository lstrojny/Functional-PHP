<?php
/**
 * Copyright (C) 2011 - 2012 by Lars Strojny <lstrojny@php.net>
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

function option($x = null)
{
    return null === $x ? Option::zero() : Option::lift($x);
}

/**
 * Similar to Scala Option or Haskell Maybe
 */
abstract class Option implements Semigroup, Monoid, Functor, Monad
{
    public abstract function isEmpty();

    /**
     * Monoid zero
     */
    public static final function zero()
    {
        return new None();
    }

    /**
     * Monad lift (aka return or pure)
     */
    public static final function lift($x)
    {
        return new Some($x);
    }
}

final class Some extends Option
{
    private $x;

    public final function __construct($x)
    {
        $this->x = $x;
    }

    public final function get()
    {
        return $this->x;
    }

    public final function isEmpty()
    {
        return false;
    }

    /**
     * Semigroup append
     */
    public final function append($o, $callback = 'Functional\\append')
    {
        $x = $this->x;
        return $o->isEmpty()
            ? $this
            : $o->map(function($y) use ($callback, $x) {
                return $callback($x, $y);
            });
    }

    public final function map($callback)
    {
        return new Some(call_user_func($callback, $this->x));
    }

    public final function bind($callback)
    {
        return call_user_func($callback, $this->x);
    }
}

final class None extends Option
{
    public final function isEmpty()
    {
        return true;
    }

    /**
     * Semigroup append
     */
    public final function append($o, $callback = 'Functional\\append')
    {
        return $o;
    }

    public final function map($callback)
    {
        return $this;
    }

    public final function bind($callback)
    {
        return $this;
    }
}
