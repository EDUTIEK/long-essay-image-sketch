<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Tests;

use PHPUnit\Framework\TestCase;
use LongEssayImageSketch\Shape\Circle;
use LongEssayImageSketch\Point;
use LongEssayImageSketch\Draw;

class CircleTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->assertInstanceOf(Circle::class, new Circle(new Point(0, 0), 'Hej', 'red'));
    }

    public function testDraw(): void
    {
        $pos = new Point(10, 10);
        $draw = $this->getMockBuilder(Draw::class)->getMock();
        $draw->expects(self::once())->method('withFillColor')->willReturnCallback(function ($color, $within) use ($draw) {
            $this->assertSame('red', $color);
            $within($draw);
        });
        $draw->expects(self::once())->method('circle')->with($pos, 5);

        $circle = new Circle($pos, 'Hej', 'red');
        $circle->draw($draw);
    }
}
