<?php

namespace Medusa\Queue;

class PersistentQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage peek empty queue
     */
    public function peekOnEmptyShouldThrowException()
    {
        PersistentQueue::createEmpty()->peek();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage dequeue empty queue
     */
    public function dequeOnEmptyShouldThrowException()
    {
        PersistentQueue::createEmpty()->dequeue();
    }

    /**
     * @test
     */
    public function lastInShouldBeFirstOut()
    {
        $q = $this->createQueue([1, 2, 3]);

        $this->assertEquals([1, 2, 3], $this->dequeueValues($q));
    }

    private function createQueue(array $values)
    {
        $q = PersistentQueue::createEmpty();
        foreach ($values as $value) {
            $q = $q->enqueue($value);
        }

        return $q;
    }

    private function dequeueValues(Queue $queue)
    {
        $values = [];
        foreach ($queue as $value) {
            $values[] = $value;
        }

        return $values;
    }

    /**
     * @test
     */
    public function dequeueShouldNotAffectPreviousVersions()
    {
        $q = $this->createQueue([1, 2, 3]);

        $q->dequeue();

        $this->assertValues([1, 2, 3], $q);
    }

    private function assertValues(array $values, Queue $queue)
    {
        $this->assertEquals($values, $this->dequeueValues($queue));
    }

    /**
     * @test
     */
    public function dequeueShouldRemoveLastElement()
    {
        $q = $this->createQueue([1, 2, 3]);

        $this->assertValues([2, 3], $q->dequeue());
    }

    /**
     * @test
     */
    public function enqueueShouldNotAffectPreviousVersions()
    {
        $s = PersistentQueue::createEmpty();

        $s->enqueue(4);

        $this->assertValues([], $s);
    }

    /**
     * @test
     */
    public function enqueueShouldAppendValue()
    {
        $s = $this->createQueue([1, 2, 3]);

        $this->assertValues([1, 2, 3, 4], $s->enqueue(4));
    }

    /**
     * @test
     * @dataProvider provideQueuesWithExpectedCount
     */
    public function itShouldCountNumberOfElements($values, $expectedCount)
    {
        $s = $this->createQueue($values);

        $this->assertEquals($expectedCount, $s->count());
    }

    public function provideQueuesWithExpectedCount()
    {
        return [
            [[], 0],
            [[1], 1],
            [[1, 2, 3], 3]
        ];
    }
}
