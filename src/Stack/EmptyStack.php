<?php

namespace Medusa\Stack;

class EmptyStack implements \IteratorAggregate, Stack
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
        return new PersistentStack($value, $this, 1);
    }

    public function pop()
    {
        throw new \RuntimeException("Can't pop empty stack");
    }

    public function reverse()
    {
        return new \EmptyIterator;
    }

    public function count()
    {
        return 0;
    }

    public function getIterator()
    {
        return new \EmptyIterator;
    }
}
