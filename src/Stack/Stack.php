<?php

namespace Medusa\Stack;

interface Stack extends \Traversable
{
    public function push($value);

    public function peek();

    public function pop();

    public function isEmpty();

    public function count();
}
