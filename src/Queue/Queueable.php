<?php

namespace Medusa\Queue;

interface Queueable extends \IteratorAggregate
{
    public function isEmpty();

    public function peek();

    public function enqueue($value);

    public function dequeue();

    public function getIterator();
}