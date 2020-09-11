<?php

namespace Alura\Calisthenics\Tests\Unit\Domain\Student;

use Alura\Calisthenics\Domain\Student\Student;
use Alura\Calisthenics\Domain\Video\Video;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function testStudentWithoutWatchedVideosHasAccess()
    {
        $student = new Student();
        self::assertTrue($student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoBefore90DaysHasAccess()
    {
        $date = new \DateTimeImmutable('89 days');
        $student = new Student();
        $student->watch(new Video(), $date);

        self::assertTrue($student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoAfter90DaysDoesntHaveAccess()
    {
        $date = new \DateTimeImmutable('-90 days');
        $student = new Student();
        $student->watch(new Video(), $date);

        self::assertFalse($student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoAfter90DaysButOtherVideosWatchedDoesntHaveAccess()
    {
        $student = new Student();
        $student->watch(new Video(), new \DateTimeImmutable('-90 days'));
        $student->watch(new Video(), new \DateTimeImmutable('-60 days'));
        $student->watch(new Video(), new \DateTimeImmutable('-30 days'));

        self::assertFalse($student->hasAccess());
    }

    public function testStudentWithFirstWatchedVideoBefore90DaysButOtherVideosWatchedHasAccess()
    {
        $student = new Student();
        $student->watch(new Video(), new \DateTimeImmutable('-89 days'));
        $student->watch(new Video(), new \DateTimeImmutable('-60 days'));
        $student->watch(new Video(), new \DateTimeImmutable('-30 days'));

        self::assertTrue($student->hasAccess());
    }
}
