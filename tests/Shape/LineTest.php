<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Tests;

use LongEssayImageSketch\Shape\Line;
use LongEssayImageSketch\Point;
use LongEssayImageSketch\Draw;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Line::class, new Line(new Point(0, 0), new Point(10, 10), 'hej', 'red'));
    }

    public function testDraw(): void
    {
        $colors = ['red', 'white'];
        $pos = new Point(1, 1);
        $end = new Point(10, 10);
        $shifted = new Point(11, 11);
        $draw = $this->getMockBuilder(Draw::class)->getMock();
        $draw->expects(self::exactly(count($colors)))->method('withFillColor')->willReturnCallback(function ($color, callable $within) use ($draw, &$colors) {
            $this->assertSame(array_shift($colors), $color);
            $within($draw);
        });
        $draw->expects(self::once())->method('polygon')->with([$pos, $shifted]);
        $draw->expects(self::once())->method('shiftBy')->with($pos, $end)->willReturn($shifted);
        $draw->expects(self::once())->method('text')->with($pos, 'Hi');

        $line = new Line($end, $pos, 'Hi', 'red');
        $line->draw($draw);
    }
}
