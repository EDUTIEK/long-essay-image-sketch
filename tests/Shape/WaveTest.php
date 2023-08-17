<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Tests;

use PHPUnit\Framework\TestCase;
use LongEssayImageSketch\Shape\Wave;
use LongEssayImageSketch\Point;
use LongEssayImageSketch\Draw;

class WaveTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Wave::class, new Wave(new Point(0, 10), new Point(0, 0), 'Hej', 'red'));
    }

    public function testDraw(): void
    {
        $pos = new Point(23, 34);
        $angle = 1/4 * pi();
        $expected_path = [function(){}, function(){}];
        $shifted = [new Point(1, 1), new Point(2, 2), new Point(3, 3), new Point(4, 4), new Point(5, 5)];

        $draw = $this->getMockBuilder(Draw::class)->getMock();
        $draw->expects(self::once())->method('withRotation')->willReturnCallback(function ($radians, $within) use ($draw, $angle) {
            $this->assertSame($angle, $radians);
            $within($draw);
        });
        $draw->expects(self::once())->method('withStrokeColor')->willReturnCallback(function ($color, $within) use ($draw) {
            $this->assertSame('yellow', $color);
            $within($draw);
        });
        $draw->expects(self::once())->method('path')->willReturnCallback(function ($start, $path) use ($draw, $angle, $expected_path) {
            $this->assertEquals(
                new Point(23 * cos(-$angle) - 34 * sin(-$angle), 23 * sin(-$angle) + 34 * cos(-$angle)),
                $start
            );
            $this->assertEquals($expected_path, $path);

        });
        $draw->expects(self::exactly(count($expected_path)))->method('quadraticCurve')->withConsecutive(
            [new Point(-12.5, -15), $shifted[0]],
            [$shifted[4], $shifted[3]],
        )->willReturnOnConsecutiveCalls(...$expected_path);
        $draw->expects(self::any())->method('shiftBy')->willReturnOnConsecutiveCalls(...$shifted);
        $draw->expects(self::once())->method('text')->with($pos, 'Hej');
        $draw->expects(self::once())->method('withFillColor')->willReturnCallback(function ($color, $within) use ($draw) {
            $this->assertSame('white', $color);
            $within($draw);
        });

        $wave = new Wave(new Point(33, 33), $pos, 'Hej', 'yellow');
        $wave->draw($draw);
    }
}
