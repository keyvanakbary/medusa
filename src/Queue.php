<?php

namespace Medusa;

class Queue implements \IteratorAggregate, Queueable
{
    private $forwards;
    private $backwards;
    private $count;

    public function __construct(Stackable $forwards, Stackable $backwards, $count)
    {
        $this->forwards = $forwards;
        $this->backwards = $backwards;
        $this->count = $count;
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
        return new Queue($this->forwards, $this->backwards->push($value), $this->count + 1);
    }

    public function dequeue()
    {
        $f = $this->forwards->pop();

        if ($f->isEmpty()) {
            return new Queue($f, $this->backwards, $this->count - 1);
        }

        if ($this->backwards->isEmpty()) {
            return Queue::createEmpty();
        }

        return new Queue($this->backwards->reverse(), Stack::createEmpty(), $this->count - 1);
    }

    public function count()
    {
        return $this->count;
    }

    public function getIterator()
    {
        foreach ($this->forwards as $value) yield $value;
        foreach ($this->backwards->reverse() as $value) yield $value;
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
        return new Queue(Stack::createEmpty()->push($value), Stack::createEmpty(), 1);
    }

    public function dequeue()
    {
        throw new \RuntimeException("Can't dequeue empty queue");
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
