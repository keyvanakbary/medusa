<?php

namespace Medusa\Tree;

require_once __DIR__ . '/PersistentBalancedTreeTest.php';

class PersistentRedBlackTreeTest extends PersistentBalancedTreeTest
{
    protected function createEmpty()
    {
        return PersistentRedBlackTree::createEmpty();
    }

    /**
     * @test
     */
    public function shouldCameOutOrdered()
    {
        $lastKey = null;
        foreach ($this->createFromArray(range(1, 50)) as $key => $value) {
            if ($lastKey) {
                $this->assertLessThanOrEqual($key, $lastKey);
            }
            $lastKey = $key;
        }
    }

    /**
     * @test
     */
    public function shouldRetrieveTheMinimum()
    {
        $randomValues = $this->generateRandomValues(50);
        $t = $this->createFromArray($randomValues);

        $this->assertEquals(min($randomValues), $t->min()->value());
    }

    private function generateRandomValues($numItems)
    {
        $randomValues = range(1, $numItems);
        shuffle($randomValues);

        return $randomValues;
    }
}
