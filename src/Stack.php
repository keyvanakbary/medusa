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
        return new StackIterator($this);
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

/**
 * @internal
 */
class StackIterator implements \Iterator
{
    private $pos = 0;
    private $current;
    private $stack;

    public function __construct(Stackable $stack)
    {
        $this->current = $stack;
        $this->stack = $stack;
    }

    public function current()
    {
        return $this->current->peek();
    }

    public function key()
    {
        return $this->pos;
    }

    public function next()
    {
        $this->current = $this->current->pop();
        $this->pos++;
    }

    public function rewind()
    {
        $this->pos = 0;
        $this->current = $this->stack;
    }

    public function valid()
    {
        return !$this->current->isEmpty();
    }
}
