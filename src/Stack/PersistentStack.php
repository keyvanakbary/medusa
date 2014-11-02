<?php

namespace Medusa\Stack;

class PersistentStack implements \IteratorAggregate, Stack
{
    private $head;
    private $tail;
    private $count;

    public function __construct($head, Stack $tail, $count)
    {
        $this->head = $head;
        $this->tail = $tail;
        $this->count = $count;
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
        return new self($value, $this, $this->count + 1);
    }

    public function pop()
    {
        return $this->tail;
    }

    public function count()
    {
        return $this->count;
    }

    public function getIterator()
    {
        for ($stack = $this; !$stack->isEmpty();) {
            yield $stack->peek();
            $stack = $stack->pop();
        }
    }
}
