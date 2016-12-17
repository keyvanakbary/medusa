<?php

namespace Medusa\Tree;

class PersistentRedBlackTree implements \IteratorAggregate, RedBlackTree
{
    private $key;
    private $color;
    private $value;
    private $left;
    private $right;
    private $isRoot;
    private $height;

    public function __construct($key, $value, RedBlackTree $left, RedBlackTree $right, $color, $isRoot)
    {
        $this->key = $key;
        $this->value = $value;
        $this->left = $left;
        $this->right = $right;
        $this->color = $color;
        $this->isRoot = $isRoot;
        $this->height = 1 + max($left->height(), $right->height());
    }

    public function height()
    {
        return $this->height;
    }

    public static function createEmpty()
    {
        return new EmptyRedBlackTree;
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

    public function color()
    {
        return $this->color;
    }

    public function isRoot()
    {
        return $this->isRoot === true;
    }

    public function key()
    {
        return $this->key;
    }

    private function isRed(RedBlackTree $t)
    {
        return $t->color() === RedBlackTree::RED;
    }

    public function add($key, $value)
    {
        $c = $this->compare($key, $this->key);

        if ($c === 0) {
            $t = new self($key, $value, $this->left, $this->right, $this->color, $this->isRoot);
        } else if ($c < 0) {
            $t = new self($this->key, $this->value, $this->addLeft($key, $value), $this->right, $this->color, $this->isRoot);
        } else {
            $t = new self($this->key, $this->value, $this->left, $this->addRight($key, $value), $this->color, $this->isRoot);
        }

        if ($this->isRed($t->right()) && !$this->isRed($t->left())) $t = $this->rotateLeft($t);
        if ($this->isRed($t->left()) && $this->isRed($t->left()->left())) $t = $this->rotateRight($t);
        if ($this->isRed($t->right()) && $this->isRed($t->left())) $t = $this->flipColors($t);

        return $t;
    }

    private function addLeft($key, $value)
    {
        return ($this->left->isEmpty()) ?
            $this->createRed($key, $value, $this->left, $this->left) :
            $this->left->add($key, $value);
    }

    private function addRight($key, $value)
    {
        return ($this->right->isEmpty()) ?
            $this->createRed($key, $value, $this->right, $this->right) :
            $this->right->add($key, $value);
    }

    private function createRed($key, $value, $left, $right)
    {
        return new self($key, $value, $left, $right, RedBlackTree::RED, false);
    }

    private function compare($key1, $key2)
    {
        if ($key1 === $key2) return 0;
        if ($key1 < $key2) return -1;

        return 1;
    }

    private function flipColors(RedBlackTree $t)
    {
        $color = $t->isRoot() ? RedBlackTree::BLACK : !$t->color();

        return
            new self($t->key(), $t->value(), $this->flipColor($t->left()), $this->flipColor($t->right()), $color, $t->isRoot());
    }

    private function flipColor(RedBlackTree $t)
    {
        return new self($t->key(), $t->value(), $t->left(), $t->right(), !$t->color(), $t->isRoot());
    }

    private function rotateRight(RedBlackTree $t)
    {
        return
            new self($t->left()->key(), $t->left()->value(), $t->left()->left(),
                $this->createRed($t->key(), $t->value(), $t->left()->right(), $t->right()),
                $t->color(), $t->isRoot());
    }


    private function rotateLeft(RedBlackTree $t)
    {
        return
            new self($t->right()->key(), $t->right()->value(),
                $this->createRed($t->key(), $t->value(), $t->left(), $t->right()->left()),
                $t->right()->right(), $t->color(), $t->isRoot());
    }

    public function remove($key)
    {
        $t = $this;

        if ($this->isRoot() && !$this->isRed($this->left()) && !$this->isRed($this->right())) {
            $t = $this->flipToRed($t);
        }

        $t = $this->removeIn($t, $key);

        if (!$t->isEmpty()) {
            $t = $this->flipToBlack($t);
        }

        return $t;
    }

    private function removeIn(RedBlackTree $t, $key)
    {
        if ($this->compare($key, $t->key()) < 0) {
            if (!$this->isRed($t->left()) && !$this->isRed($t->left()->left())) {
                $t = $this->moveRedLeft($t);
            }
            $t = new self($t->key(), $t->value(), $t->left()->remove($key), $t->right(), $t->color(), $t->isRoot());
        } else {
            if ($this->isRed($t->left())) {
                $t = $this->rotateRight($t);
            }
            if ($this->compare($key, $t->key()) === 0 && $t->right()->isEmpty()) {
                return self::createEmpty();
            }
            if (!$this->isRed($t->right()) && !$this->isRed($t->right()->left())) {
                $t = $this->moveRedRight($t);
            }
            if ($this->compare($key, $t->key()) === 0) {
                $min = $this->minIn($t->right());
                $t = new self($min->key(), $min->value(), $t->left(), $this->removeMinIn($t->right()), $t->color(), $t->isRoot());
            } else {
                $t = new self($t->key(), $t->value(), $t->left(), $t->right()->remove($key), $t->color(), $t->isRoot());
            }
        }

        return $this->balance($t);
    }

    private function moveRedLeft(RedBlackTree $t)
    {
        $t = $this->flipColors($t);

        if (!$this->isRed($t->right()->left())) {
            return $t;
        }

        return $this->rotateLeft(
            new self($t->key(), $t->value(), $t->left(), $this->rotateRight($t->right()), $t->color(), $t->isRoot()));
    }

    private function moveRedRight(RedBlackTree $t)
    {
        $t = $this->flipColors($t);

        if (!$this->isRed($t->left()->left())) {
            return $t;
        }

        return $this->rotateRight($t);
    }

    private function balance(RedBlackTree $t)
    {
        if ($this->isRed($t->right())) $t = $this->rotateLeft($t);
        if ($this->isRed($t->left()) && $this->isRed($t->left()->left())) $t = $this->rotateRight($t);
        if ($this->isRed($t->left()) && $this->isRed($t->right())) $t = $this->flipColors($t);

        return $t;
    }

    public function removeMin()
    {
        $t = $this;

        if ($t->isRoot() && !$this->isRed($t->left()) && !$this->isRed($t->right())) {
            $t = $this->flipToRed($t);
        }

        $t = $this->removeMinIn($t);

        if ($t->isRoot() && $t->isEmpty()) {
            $t = $this->flipToBlack($t);
        }

        return $t;
    }

    private function removeMinIn(RedBlackTree $t)
    {
        if ($t->left()->isEmpty()) return self::createEmpty();

        if (!$this->isRed($t->left()) && !$this->isRed($t->left()->left())) {
            $t = $this->moveRedLeft($t);
        }

        return $this->balance(
            new self($t->key(), $t->value(), $this->removeMinIn($t->left()), $t->right(), $t->color(), $t->isRoot()));
    }

    private function flipToBlack(RedBlackTree $t)
    {
        return new self($t->key(), $t->value(), $t->left(), $t->right(), RedBlackTree::BLACK, $t->isRoot());
    }

    private function flipToRed(RedBlackTree $t)
    {
        return new self($t->key(), $t->value(), $t->left(), $t->right(), RedBlackTree::RED, $t->isRoot());
    }

    public function min()
    {
        return $this->minIn($this);
    }

    private function minIn(RedBlackTree $t)
    {
        while(!$t->left()->isEmpty()) {
            $t = $t->left();
        }

        return $t;
    }

    public function getIterator()
    {
        $t = $this;
        while(!$t->isEmpty()) {
            $min = $t->min();

            yield $min->key() => $min->value();

            $t = $t->removeMin();
        }
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

    public function search($key)
    {
        $c = $this->compare($key, $this->key);

        if ($c === 0) {
            return $this;
        } else if ($c > 0) {
            return $this->right->search($key);
        } else {
            return $this->left->search($key);
        }
    }
}
