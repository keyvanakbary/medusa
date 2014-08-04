<?php

namespace Medusa\Queue;

interface Queue extends \Traversable
{
    public function isEmpty();

    public function peek();

    public function enqueue($value);

    public function dequeue();

    public function count();
}
