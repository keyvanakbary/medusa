<?php

namespace Medusa\Queue;

use Medusa\Stack\Stackable;

class QueueIterator implements \Iterator
{
    private $pos = 0;
    private $forwards;
    private $backwards;
    private $currentForwards;
    private $currentBackwards;

    public function __construct(Stackable $forwards, Stackable $backwards)
    {
        $this->forwards = $forwards;
        $this->backwards = $backwards->reverse();
        $this->currentForwards = $forwards;
        $this->currentBackwards = $backwards;
    }

    public function current()
    {
        return ($this->currentForwards->isEmpty()) ?
            $this->currentBackwards->peek() :
            $this->currentForwards->peek();
    }

    public function next()
    {
        if ($this->currentForwards->isEmpty()) {
            $this->currentBackwards = $this->currentBackwards->pop();
        } else {
            $this->currentForwards = $this->currentForwards->pop();
        }
        $this->pos++;
    }

    public function key()
    {
        return $this->pos;
    }

    public function valid()
    {
        return
            !$this->currentForwards->isEmpty() ||
            !$this->currentBackwards->isEmpty();
    }

    public function rewind()
    {
        $this->pos = 0;
        $this->currentForwards = $this->forwards;
        $this->currentBackwards = $this->backwards;
    }
}
