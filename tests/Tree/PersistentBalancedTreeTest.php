<?php

namespace Medusa\Tree;

abstract class PersistentBalancedTreeTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function createEmpty();

    /**
     * @test
     */
    public function shouldAddKeyWithValue()
    {
        $t = $this->createEmpty()->add(1, 'value');

        $this->assertEquals('value', $t->lookup(1));
    }

    /**
     * @test
     */
    public function shouldRemoveKey()
    {
        $t = $this->createEmpty()->add(1, 'value')->remove(1);

        $this->assertFalse($t->contains(1));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage not found
     */
    public function lookupOnNonExistingKeyShouldThrowException()
    {
        $this->createEmpty()->lookup(1);
    }

    /**
     * @test
     */
    public function shouldReplaceKey()
    {
        $t = $this->createEmpty()
            ->add(1, 'first')
            ->add(1, 'last');

        $this->assertEquals('last', $t->lookup(1));
    }

    /**
     * @test
     */
    public function addShouldNotModifyPreviousVersions()
    {
        $t1 = $this->createEmpty()->add(1, 'first');
        $t1->add(1, 'last');

        $this->assertEquals('first', $t1->lookup(1));
    }

    /**
     * @test
     */
    public function removeShouldNotModifyPreviousVersions()
    {
        $t = $this->createEmpty()->add(1, 'first');
        $t->remove(1);

        $this->assertTrue($t->contains(1));
    }

    /**
     * @test
     */
    public function shouldBeBalanced()
    {
        for ($i = 0; $i < 10; $i++) {
            $numItems = mt_rand(0, 500);
            $t = $this->createFromArray(range(1, $numItems));

            $this->assertHeightIsLogarithmic($numItems, $t->height());
        }
    }

    private function assertHeightIsLogarithmic($numItems, $height)
    {
        $expected = ceil(log($numItems + 1, 2));

        $this->assertLessThanOrEqual($expected, $height);
    }

    protected function createFromArray(array $values)
    {
        $t = $this->createEmpty();
        foreach ($values as $value) {
            $t = $t->add($value, $value);
        }

        return $t;
    }

    /**
     * @test
     */
    public function shouldContainValues()
    {
        $t = $this->createEmpty()
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
