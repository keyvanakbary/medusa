<?php

namespace Medusa\Tree;

interface AvlTree extends \Traversable
{
    public function isEmpty();

    public function value();

    public function left();

    public function right();

    public function key();

    public function search($key);

    public function add($key, $value);

    public function remove($key);

    public function contains($key);

    public function height();

    public function lookup($key);
}
