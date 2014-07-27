<?php

namespace Medusa\Stack;

class EmptyStack implements Stackable
{
    public function isEmpty()
    {
        return true;
    }

    public function peek()
    {
        throw new StackIsEmpty;
    }

    public function push($value)
    {
        return new Stack($value, $this);
    }

    public function pop()
    {
        throw new StackIsEmpty;
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