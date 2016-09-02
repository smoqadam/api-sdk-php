<?php

namespace test\eLife\ApiSdk\Client;

use eLife\ApiClient\ApiClient\SubjectsClient;
use eLife\ApiSdk\Client\Subjects;
use eLife\ApiSdk\Collection;
use eLife\ApiSdk\Model\Subject;
use test\eLife\ApiSdk\ApiTestCase;

final class SubjectsTest extends ApiTestCase
{
    /** @var SubjectsClient */
    private $subjectsClient;

    /**
     * @before
     */
    protected function setUpSubjectsClient()
    {
        $this->subjectsClient = new SubjectsClient($this->getHttpClient());
    }

    /**
     * @test
     */
    public function it_is_a_collection()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->assertInstanceOf(Collection::class, $subjects);
    }

    /**
     * @test
     */
    public function it_can_be_traversed()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 200);
        $this->mockSubjectListCall(1, 100, 200);
        $this->mockSubjectListCall(2, 100, 200);

        foreach ($subjects as $i => $subject) {
            $this->assertInstanceOf(Subject::class, $subject);
            $this->assertSame('subject'.$i, $subject->getId());
        }
    }

    /**
     * @test
     */
    public function it_can_be_counted()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 10);

        $this->assertSame(10, $subjects->count());
    }

    /**
     * @test
     */
    public function it_casts_to_an_array()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 10);
        $this->mockSubjectListCall(1, 100, 10);

        $array = $subjects->toArray();

        $this->assertCount(10, $array);

        foreach ($array as $i => $subject) {
            $this->assertInstanceOf(Subject::class, $subject);
            $this->assertSame('subject'.($i + 1), $subject->getId());
        }
    }

    /**
     * @test
     */
    public function it_gets_a_subject()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectCall(7);

        $subject = $subjects->get('subject7')->wait();

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertSame('subject7', $subject->getId());
    }

    /**
     * @test
     */
    public function it_reuses_already_known_subjects()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 10);
        $this->mockSubjectListCall(1, 100, 10);

        $subjects->toArray();

        $subject = $subjects->get('subject7')->wait();

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertSame('subject7', $subject->getId());
    }

    /**
     * @test
     * @dataProvider sliceProvider
     */
    public function it_can_be_sliced(int $offset, int $length = null, array $expected, array $calls)
    {
        $subjects = new Subjects($this->subjectsClient);

        foreach ($calls as $call) {
            $this->mockSubjectListCall($call['page'], $call['per-page'], 5);
        }

        foreach ($subjects->slice($offset, $length) as $i => $subject) {
            $this->assertInstanceOf(Subject::class, $subject);
            $this->assertSame('subject'.($expected[$i]), $subject->getId());
        }
    }

    public function sliceProvider() : array
    {
        return [
            'offset 1, length 1' => [
                1,
                1,
                [2],
                [
                    ['page' => 2, 'per-page' => 1],
                ],
            ],
            'offset -2, no length' => [
                -2,
                null,
                [4, 5],
                [
                    ['page' => 1, 'per-page' => 1],
                    ['page' => 1, 'per-page' => 100],
                ],
            ],
            'offset 6, no length' => [
                6,
                null,
                [],
                [
                    ['page' => 1, 'per-page' => 1],
                    ['page' => 1, 'per-page' => 100],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider sliceProvider
     */
    public function it_can_be_mapped()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 3);
        $this->mockSubjectListCall(1, 100, 3);

        $map = function (Subject $subject) {
            return $subject->getId();
        };

        $this->assertSame(['subject1', 'subject2', 'subject3'], $subjects->map($map)->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_filtered()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 5);
        $this->mockSubjectListCall(1, 100, 5);

        $filter = function (Subject $subject) {
            return substr($subject->getId(), -1) > 3;
        };

        foreach ($subjects->filter($filter) as $i => $subject) {
            $this->assertSame('subject'.($i + 4), $subject->getId());
        }
    }

    /**
     * @test
     */
    public function it_can_be_reduced()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 5);
        $this->mockSubjectListCall(1, 100, 5);

        $reduce = function (int $carry = null, Subject $subject) {
            return $carry + substr($subject->getId(), -1);
        };

        $this->assertSame(115, $subjects->reduce($reduce, 100)->wait());
    }

    /**
     * @test
     */
    public function it_can_be_sorted()
    {
        $subjects = new Subjects($this->subjectsClient);

        $this->mockSubjectListCall(1, 1, 5);
        $this->mockSubjectListCall(1, 100, 5);

        $sort = function (Subject $a, Subject $b) {
            return substr($b->getId(), -1) <=> substr($a->getId(), -1);
        };

        foreach ($subjects->sort($sort) as $i => $subject) {
            $this->assertSame('subject'.(5 - $i), $subject->getId());
        }
    }
}
