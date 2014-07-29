<?php

namespace Medusa\Queue;

use Medusa\Stack\Stack;
use Medusa\Stack\Stackable;

class Queue implements \IteratorAggregate, Queueable
{
    private $forwards;
    private $backwards;

    public function __construct(Stackable $forwards, Stackable $backwards)
    {
        $this->forwards = $forwards;
        $this->backwards = $backwards;
    }

    public static function createEmpty()
    {
        return new EmptyQueue;
    }

    public function isEmpty()
    {
        return false;
    }

    public function peek()
    {
        try {
            return $this->forwards->peek();
        } catch (\RuntimeException $e) {
            throw new \RuntimeException("Can't peek empty queue");
        }
    }

    public function enqueue($value)
    {
        return new Queue($this->forwards, $this->backwards->push($value));
    }

    public function dequeue()
    {
        $f = $this->forwards->pop();

        if ($f->isEmpty()) {
            return new Queue($f, $this->backwards);
        }

        if ($this->backwards->isEmpty()) {
            return Queue::createEmpty();
        }

        return new Queue($this->backwards->reverse(), Stack::createEmpty());
    }

    public function getIterator()
    {
        return new QueueIterator($this->forwards, $this->backwards);
    }
}
