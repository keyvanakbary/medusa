<?php

namespace Medusa\Tree;

class EmptyBinaryTree implements \IteratorAggregate, BinaryTree
{
    public function isEmpty()
    {
        return true;
    }

    public function value()
    {
        throw new \RuntimeException("Can't get value of empty tree");
    }

    public function left()
    {
        throw new \RuntimeException("Can't get left of empty tree");
    }

    public function right()
    {
        throw new \RuntimeException("Can't get right of empty tree");
    }

    public function key()
    {
        throw new \RuntimeException("Can't get key of empty tree");
    }

    public function search($key)
    {
        return $this;
    }

    public function add($key, $value)
    {
        return new PersistentBinaryTree($key, $value, $this, $this);
    }

    public function remove($key)
    {
        throw new \RuntimeException("Can't remove item that is not in tree");
    }

    public function height()
    {
        return 0;
    }

    public function contains($key)
    {
        return false;
    }

    public function lookup($key)
    {
        throw new \RuntimeException("Key not found in tree");
    }

    public function getIterator()
    {
        return new \EmptyIterator;
    }
}
