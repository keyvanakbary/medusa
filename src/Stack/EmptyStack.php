<?php

namespace Medusa\Stack;

class EmptyStack implements \IteratorAggregate, Stackable
{
    public function isEmpty()
    {
        return true;
    }

    public function peek()
    {
        throw new \RuntimeException("Can't peek empty stack");
    }

    public function push($value)
    {
        return new Stack($value, $this);
    }

    public function pop()
    {
        throw new \RuntimeException("Can't pop empty stack");
    }

    public function reverse()
    {
        return new \EmptyIterator;
    }

    public function getIterator()
    {
        return new \EmptyIterator;
    }
}
