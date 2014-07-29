<?php

namespace Medusa;

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

/**
 * @internal
 */
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

/**
 * @internal
 */
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
