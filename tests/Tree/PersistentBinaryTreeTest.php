<?php

namespace Medusa\Tree;

class PersistentBinaryTreeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAddKeyWithValue()
    {
        $t = PersistentBinaryTree::createEmpty()->add(1, 'value');

        $this->assertEquals('value', $t->lookup(1));
    }

    /**
     * @test
     */
    public function shouldRemoveKey()
    {
        $t = PersistentBinaryTree::createEmpty()->add(1, 'value')->remove(1);

        $this->assertFalse($t->contains(1));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage not found
     */
    public function lookupOnNonExistingKeyShouldThrowException()
    {
        PersistentBinaryTree::createEmpty()->lookup(1);
    }

    /**
     * @test
     */
    public function shouldReplaceKey()
    {
        $t = PersistentBinaryTree::createEmpty()
            ->add(1, 'first')
            ->add(1, 'last');

        $this->assertEquals('last', $t->lookup(1));
    }

    /**
     * @test
     */
    public function addShouldNotModifyPreviousVersions()
    {
        $t1 = PersistentBinaryTree::createEmpty()->add(1, 'first');
        $t1->add(1, 'last');

        $this->assertEquals('first', $t1->lookup(1));
    }

    /**
     * @test
     */
    public function removeShouldNotModifyPreviousVersions()
    {
        $t = PersistentBinaryTree::createEmpty()->add(1, 'first');
        $t->remove(1);

        $this->assertTrue($t->contains(1));
    }

    /**
     * @test
     * @dataProvider numItems
     */
    public function shouldBeBalanced($numItems)
    {
        $t = $this->createTree($numItems);

        $this->assertLessThanOrEqual($this->heightInvariantFor($numItems), $t->height());
    }

    private function heightInvariantFor($numItems)
    {
        return ceil(log($numItems + 1, 2));
    }

    private function createTree($numItems)
    {
        $t = PersistentBinaryTree::createEmpty();
        for ($j = 0; $j < $numItems; $j++) {
            $t = $t->add($j, $j);
        }

        return $t;
    }

    public function numItems()
    {
        return [[2], [4], [8], [16], [32], [64], [128], [256], [512]];
    }

    /**
     * @test
     */
    public function shouldContainValues()
    {
        $t = PersistentBinaryTree::createEmpty()
            ->add(1, 'one')
            ->add(2, 'two')
            ->add(3, 'three');

        $this->assertContainValues([1 => 'one', 2 => 'two', 3 => 'three'], $t);
    }

    private function assertContainValues($expected, $tree)
    {
        $values = [];
        foreach ($tree as $key => $value) {
            $values[$key] = $value;
        }

        $this->assertEquals($expected, $values);
    }
}
