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
     * @dataProvider balancedTrees
     */
    public function shouldBeBalanced($numItems, $expectedHeight)
    {
        $t = PersistentBinaryTree::createEmpty();
        for ($i = 0; $i < $numItems; $i++) {
            $t = $t->add($i, $i);
        }

        $this->assertEquals($expectedHeight, $t->height());
    }

    public function balancedTrees()
    {
        return [
            [1, 1],
            [3, 2],
            [7, 3],
            [15, 4],
            [17, 5],
        ];
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

        $this->assertContainValues(array(1 => 'one', 2 => 'two', 3 => 'three'), $t);
    }

    private function assertContainValues($expected, $tree)
    {
        $values = array();
        foreach ($tree as $key => $value) {
            $values[$key] = $value;
        }

        $this->assertEquals($expected, $values);
    }
}
