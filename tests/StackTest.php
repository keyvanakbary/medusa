<?php

namespace Medusa;

class StackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage peek empty stack
     */
    public function peekOnEmptyShouldThrowException()
    {
        Stack::createEmpty()->peek();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage pop empty stack
     */
    public function popOnEmptyShouldThrowException()
    {
        Stack::createEmpty()->pop();
    }

    /**
     * @test
     */
    public function firstInShouldBeFirstOut()
    {
        $s = $this->createStack(array(1, 2, 3));

        $this->assertValues(array(3, 2, 1), $s);
    }

    private function createStack(array $values)
    {
        $s = Stack::createEmpty();
        foreach($values as $value) {
            $s = $s->push($value);
        }

        return $s;
    }

    private function assertValues(array $values, Stackable $s)
    {
        $this->assertEquals($values, $this->popValues($s));
    }

    private function popValues(Stackable $stack)
    {
        $values = array();
        foreach ($stack as $value) {
            $values[] = $value;
        }

        return $values;
    }

    /**
     * @test
     */
    public function popShouldNotAffectPreviousVersions()
    {
        $s = $this->createStack([1, 2, 3]);

        $s->pop();

        $this->assertValues([3, 2, 1], $s);
    }

    /**
     * @test
     */
    public function popShouldRemoveHead()
    {
        $s = $this->createStack([1, 2, 3]);

        $this->assertValues([2, 1], $s->pop());
    }

    /**
     * @test
     */
    public function pushShouldNotAffectPreviousVersions()
    {
        $s = Stack::createEmpty();

        $s->push(4);

        $this->assertValues([], $s);
    }

    /**
     * @test
     */
    public function pushShouldAddNewHead()
    {
        $s = $this->createStack([1, 2, 3]);

        $this->assertValues([4, 3, 2, 1], $s->push(4));
    }

    /**
     * @test
     */
    public function itShouldReverseTheValues()
    {
        $s = $this->createStack([1, 2, 3]);

        $this->assertValues([1, 2, 3], $s->reverse());
    }

    /**
     * @test
     * @dataProvider provideStacksWithExpectedCount
     */
    public function itShouldCountNumberOfElements($values, $expectedCount)
    {
        $s = $this->createStack($values);

        $this->assertEquals($expectedCount, $s->count());
    }

    public function provideStacksWithExpectedCount()
    {
        return array(
            array([], 0),
            array([1], 1),
            array([1, 2, 3], 3)
        );
    }
}
