<?php

namespace Medusa\Stack;

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