<?php

namespace Medusa\Stack;

class Stack implements \IteratorAggregate, Stackable
{
    private $head;
    private $tail;

    public function __construct($head, Stackable $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
    }

    public static function createEmpty()
    {
        return new EmptyStack;
    }

    public function isEmpty()
    {
        return false;
    }

    public function peek()
    {
        return $this->head;
    }

    public function push($value)
    {
        return new Stack($value, $this);
    }

    public function pop()
    {
        return $this->tail;
    }

    public function reverse()
    {
        $s = new EmptyStack;
        foreach ($this->getIterator() as $value) {
            $s = $s->push($value);
        }

        return $s;
    }

    public function getIterator()
    {
        return new StackIterator($this);
    }
}
