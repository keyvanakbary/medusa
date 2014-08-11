<?php

namespace Medusa\Tree;

interface RedBlackTree extends \Traversable, BinaryTree
{
    const RED = true;
    const BLACK = false;

    public function color();

    public function isRoot();

    public function removeMin();
}
