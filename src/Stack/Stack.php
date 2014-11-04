<?php

namespace Medusa\Stack;

interface Stack extends \Traversable, \Countable
{
    public function push($value);

    public function peek();

    public function pop();

    public function isEmpty();
}
