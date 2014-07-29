<?php

namespace Medusa\Queue;

use Medusa\Stack\Stack;

class EmptyQueue implements \IteratorAggregate, Queueable
{
    public function isEmpty()
    {
        return true;
    }

    public function peek()
    {
        throw new \RuntimeException("Can't peek empty queue");
    }

    public function enqueue($value)
    {
        return new Queue(Stack::createEmpty()->push($value), Stack::createEmpty());
    }

    public function dequeue()
    {
        throw new \RuntimeException("Can't dequeue empty queue");
    }

    public function getIterator()
    {
        return new \EmptyIterator;
    }
}
