<?php

namespace Medusa\Tree;

class EmptyRedBlackTree implements \IteratorAggregate, RedBlackTree
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

    public function color()
    {
        return RedBlackTree::BLACK;
    }

    public function isRoot()
    {
        return false;
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
        return new PersistentRedBlackTree($key, $value, $this, $this, PersistentRedBlackTree::BLACK, true);
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

    public function removeMin()
    {
        throw new \RuntimeException("Can't remove min of empty tree");
    }

    public function getIterator()
    {
        return new \EmptyIterator;
    }
}
