<?php

namespace Medusa\Tree;

use Medusa\Stack\PersistentStack;

class PersistentAvlTree implements \IteratorAggregate, BinaryTree
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
        return new EmptyAvlTree;
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
            return new self($key, $value, $this->left, $this->right);
        } else if ($c > 0) {
            return $this->makeBalanced(new self($this->key, $this->value, $this->left, $this->right->add($key, $value)));
        } else {
            return $this->makeBalanced(new self($this->key, $this->value, $this->left->add($key, $value), $this->right));
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
            $t = $this->removeCurrentNode();
        } else if ($c > 0) {
            $t = new self($this->key, $this->value, $this->left, $this->right->remove($this->key));
        } else {
            $t = new self($this->key, $this->value, $this->left->remove($this->key), $this->right);
        }

        return $this->makeBalanced($t);
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

        return new self($next->key(), $next->value(), $this->left, $this->right->remove($next->key()));
    }

    private function makeBalanced(BinaryTree $t)
    {
        if ($this->isRightHeavy($t)) {
            if ($this->isLeftHeavy($t->right())) {
                return $this->doubleLeft($t);
            }

            return $this->rotateLeft($t);
        }

        if ($this->isLeftHeavy($t)) {
            if ($this->isRightHeavy($t->left())) {
                return $this->doubleRight($t);
            }

            return $this->rotateRight($t);
        }

        return $t;
    }

    private function isRightHeavy(BinaryTree $t)
    {
        return $this->balance($t) >= 2;
    }

    private function balance(BinaryTree $t)
    {
        if ($t->isEmpty()) {
            return 0;
        }

        return $t->right()->height() - $t->left()->height();
    }

    public function height()
    {
        return $this->height;
    }

    private function isLeftHeavy(BinaryTree $t)
    {
        return $this->balance($t) <= -2;
    }

    public function contains($key)
    {
        return !$this->search($key)->isEmpty();
    }

    public function lookup($key)
    {
        $t = $this->search($key);

        if ($t->isEmpty()) {
            throw new \RuntimeException('Key not found in tree');
        }

        return $t->value();
    }

    private function doubleLeft(BinaryTree $t)
    {
        if ($t->right()->isEmpty()) {
            return $t;
        }

        return
            $this->rotateLeft(new self($t->key(), $t->value(), $t->left(),
                $this->rotateRight($t->right())));
    }

    private function rotateLeft(BinaryTree $t)
    {
        if ($t->right()->isEmpty()) {
            return $t;
        }

        return
            new self($t->right()->key(), $t->right()->value(), $t->right()->left(),
                new self($t->key(), $t->value(), $t->left(), $t->right()->left()));
    }

    private function doubleRight(BinaryTree $t)
    {
        if ($t->left()->isEmpty()) {
            return $t;
        }

        return
            $this->rotateRight(new self($t->key(), $t->value(),
                $this->rotateLeft($t->left()), $t->right()));
    }

    private function rotateRight(BinaryTree $t)
    {
        if ($t->left()->isEmpty()) {
            return $t;
        }

        return
            new self($t->left()->key(), $t->left()->value(), $t->left()->left(),
                new self($t->key(), $t->value(), $t->left()->right(), $t->right()));
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
