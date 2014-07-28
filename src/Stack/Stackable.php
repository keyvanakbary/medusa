<?php

namespace Medusa\Stack;

interface Stackable extends \Traversable
{
    public function push($value);

    public function peek();

    public function pop();

    public function isEmpty();

    public function reverse();
}