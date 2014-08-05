<?php

namespace Medusa\Tree;

use Medusa\Stack\PersistentStack;

class PersistentBinaryTree implements \IteratorAggregate, BinaryTree
{
    private $key;
    private $value;
    private $left;
    private $right;

    public function __construct($key, $value, BinaryTree $left, BinaryTree $right)
    {
        $this->key = $key;
        $this->value = $value;
        $this->left = $left;
        $this->right = $right;
        $this->height = 1 + max($left->height(), $right->height());
    }

    public static function createEmpty()
    {
        return new EmptyBinaryTree;
    }

    public function isEmpty()
    {
        return false;
    }

    public function value()
    {
        return $this->value;
    }

    public function left()
    {
        return $this->left;
    }

    public function right()
    {
        return $this->right;
    }

    public function key()
    {
        return $this->key;
    }

    public function search($key)
    {
        $c = $this->compare($this->key, $key);

        if ($c === 0) {
            return $this;
        } else if ($c > 0) {
            return $this->right->search($key);
        } else {
            return $this->left->search($key);
        }
    }

    public function add($key, $value)
    {
        $c = $this->compare($this->key, $key);

        if ($c === 0) {
            return new PersistentBinaryTree($key, $value, $this->left, $this->right);
        } else if ($c > 0) {
            return $this->makeBalanced(new PersistentBinaryTree($this->key, $this->value, $this->left, $this->right->add($key, $value)));
        } else {
            return $this->makeBalanced(new PersistentBinaryTree($this->key, $this->value, $this->left->add($key, $value), $this->right));
        }
    }

    private function compare($key1, $key2)
    {
        if ($key1 === $key2) return 0;
        if ($key1 < $key2) return -1;
        return 1;
    }

    public function remove($key)
    {
        $c = $this->compare($this->key, $key);

        if ($c === 0) {
            $tree = $this->removeCurrentNode();
        } else if ($c > 0) {
            $tree = new PersistentBinaryTree($this->key, $this->value, $this->left, $this->right->remove($this->key));
        } else {
            $tree = new PersistentBinaryTree($this->key, $this->value, $this->left->remove($this->key), $this->right);
        }

        return $this->makeBalanced($tree);
    }

    private function removeCurrentNode()
    {
        if ($this->right->isEmpty() && $this->left->isEmpty()) {
            return self::createEmpty();
        }

        if ($this->right->isEmpty() && !$this->left->isEmpty()) {
            return $this->left;
        }

        if (!$this->right->isEmpty() && !$this->left->isEmpty()) {
            return $this->right;
        }

        return $this->removeLastAndPlaceAtTheTop();
    }

    private function removeLastAndPlaceAtTheTop()
    {
        for ($next = $this->right(); $next->left()->isEmpty();) {
            $next = $next->left();
        }

        return new PersistentBinaryTree($next->key(), $next->value(), $this->left, $this->right->remove($next->key()));
    }

    private function makeBalanced(BinaryTree $tree)
    {
        if ($this->isRightHeavy($tree)) {
            if ($this->isLeftHeavy($tree->right())) {
                return $this->doubleLeft($tree);
            }

            return $this->rotateLeft($tree);
        }

        if ($this->isLeftHeavy($tree)) {
            if ($this->isRightHeavy($tree->left())) {
                return $this->doubleRight($tree);
            }

            return $this->rotateRight($tree);
        }

        return $tree;
    }

    private function isRightHeavy(BinaryTree $tree)
    {
        return $this->balance($tree) >= 2;
    }

    private function balance(BinaryTree $tree)
    {
        if ($tree->isEmpty()) {
            return 0;
        }

        return $tree->right()->height() - $tree->left()->height();
    }

    public function height()
    {
        return $this->height;
    }

    private function isLeftHeavy($tree)
    {
        return $this->balance($tree) <= -2;
    }

    public function contains($key)
    {
        return !$this->search($key)->isEmpty();
    }

    public function lookup($key)
    {
        $tree = $this->search($key);

        if ($tree->isEmpty()) {
            throw new \RuntimeException('Key not found in tree');
        }

        return $tree->value();
    }

    private function doubleLeft(BinaryTree $tree)
    {
        if ($tree->right()->isEmpty()) {
            return $tree;
        }

        return
            $this->rotateLeft(new PersistentBinaryTree($tree->key(), $tree->value(), $tree->left(),
                $this->rotateRight($tree->right())));
    }

    private function rotateLeft(BinaryTree $tree)
    {
        if ($tree->right()->isEmpty()) {
            return $tree;
        }

        return
            new PersistentBinaryTree($tree->right()->key(), $tree->right()->value(), $tree->right()->left(),
                new PersistentBinaryTree($tree->key(), $tree->value(), $tree->left(), $tree->right()->left()));
    }

    private function doubleRight(BinaryTree $tree)
    {
        if ($tree->left()->isEmpty()) {
            return $tree;
        }

        return
            $this->rotateRight(new PersistentBinaryTree($tree->key(), $tree->value(),
                $this->rotateLeft($tree->left()), $tree->right()));
    }

    private function rotateRight(BinaryTree $tree)
    {
        if ($tree->left()->isEmpty()) {
            return $tree;
        }

        return
            new PersistentBinaryTree($tree->left()->key(), $tree->left()->value(), $tree->left()->left(),
                new PersistentBinaryTree($tree->key(), $tree->value(), $tree->left()->right(), $tree->right()));
    }

    public function getIterator()
    {
        $stack = PersistentStack::createEmpty();
        for ($current = $this; !$current->isEmpty() || !$stack->isEmpty(); $current = $current->right()) {
            while(!$current->isEmpty()) {
                $stack = $stack->push($current);
                $current = $current->left();
            }
            $current = $stack->peek();
            $stack = $stack->pop();

            yield $current->key() => $current->value();
        }
    }
}
