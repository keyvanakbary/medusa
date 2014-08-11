<?php

namespace Medusa\Tree;

require_once __DIR__ . '/PersistentBalancedTreeTest.php';

class PersistentAvlTreeTest extends PersistentBalancedTreeTest
{
    protected function createEmpty()
    {
        return PersistentAvlTree::createEmpty();
    }
}
