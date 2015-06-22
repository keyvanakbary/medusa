<?php

namespace Medusa\Queue;

use Medusa\Stack\Stack;
use Medusa\Stack\PersistentStack;

class PersistentQueue implements \IteratorAggregate, Queue
{
    private $forwards;
    private $backwards;
    private $count;

    public function __construct(Stack $forwards, Stack $backwards, $count)
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
        return new self($this->forwards, $this->backwards->push($value), $this->count + 1);
    }

    public function dequeue() {
        $f = $this->forwards->pop();

        if ($f->isEmpty() && $this->backwards->isEmpty()) {
            return self::createEmpty();
        }

        if ($f->isEmpty()) {
            return new self($this->reversedBackwards(), PersistentStack::createEmpty(), $this->count - 1);
        }

        return new self($f, $this->backwards, $this->count - 1);
    }

    private function reversedBackwards()
    {
        $s = PersistentStack::createEmpty();
        foreach ($this->backwards as $value) {
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
        foreach ($this->forwards as $value) yield $value;
        foreach ($this->reversedBackwards() as $value) yield $value;
    }
}
