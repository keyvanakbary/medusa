<?php

namespace Medusa;

class Stack implements \IteratorAggregate, Stackable
{
    private $head;
    private $tail;
    private $count;

    public function __construct($head, Stackable $tail, $count)
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
        return new Stack($value, $this, $this->count + 1);
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

/**
 * @internal
 */
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
        return new Stack($value, $this, 1);
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
