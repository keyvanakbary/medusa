<?php

namespace Medusa;

interface Queueable extends \Traversable
{
    public function isEmpty();

    public function peek();

    public function enqueue($value);

    public function dequeue();

    public function count();
}
