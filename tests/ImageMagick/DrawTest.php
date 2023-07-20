<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Tests\ImageMagick;

use PHPUnit\Framework\TestCase;
use LongEssayImageSketch\ImageMagick\Draw;
use LongEssayImageSketch\Shape;
use LongEssayImageSketch\Point;
use ImagickDraw;
use ImagickPixel;
use Closure;

class DrawTest extends TestCase
{
    public function testConstruct(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf(Draw::class, new Draw($magic));
    }

    public function testPath(): void
    {
        $called_times = 0;
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('pathStart');
        $magic->expects(self::once())->method('pathFinish');
        $magic->expects(self::once())->method('pathMoveToAbsolute')->with(10, 11);

        $segment = function ($draw) use ($magic, &$called_times) {
            $called_times++;
            $this->assertSame($magic, $draw);
        };

        $draw = new Draw($magic);
        $draw->path(new Point(10, 11), [$segment, $segment, $segment]);
        $this->assertSame(3, $called_times);
    }

    public function testLineTo(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('pathLineToAbsolute')->with(3, 4);

        $draw = new Draw($magic);
        $draw_line = $draw->lineTo(new Point(3, 4));
        $this->assertInstanceOf(Closure::class, $draw_line);

        $draw_line($magic);
    }

    public function testQuadraticCurve(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('pathCurveToQuadraticBezierAbsolute')->with(8, 10, 5, 6);

        $draw = new Draw($magic);
        $draw_curve = $draw->quadraticCurve(new Point(3, 4), new Point(5, 6));
        $this->assertInstanceOf(Closure::class, $draw_curve);
        $draw_curve($magic);
    }

    public function testWithRotation(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::exactly(2))->method('rotate')->withConsecutive([45], [-45]);

        $draw = new Draw($magic);
        $draw->withRotation(1/4 * pi(), function ($d) use ($draw) {
            $this->assertSame($draw, $d);
        });
    }

    public function testPolygonWithEmptyPoints(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::never())->method('pathStart');

        $draw = new Draw($magic);
        $draw->polygon([]);
    }

    public function testPolygonWithPoints(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('pathStart');
        $magic->expects(self::once())->method('pathFinish');
        $magic->expects(self::once())->method('pathMoveToAbsolute')->with(23, 32);
        $magic->expects(self::once())->method('pathLineToAbsolute')->with(9, 8);

        $draw = new Draw($magic);
        $draw->polygon([new Point(23, 32), new Point(9, 8)]);
    }

    public function testCircle(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('circle')->with(34, 45, 46, 57);

        $draw = new Draw($magic);
        $draw->circle(new Point(34, 45), 12);
    }

    public function testWithFillColor(): void
    {
        $green = $this->getMockBuilder(ImagickPixel::class)->disableOriginalConstructor()->getMock();
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('getFillColor')->willReturn($green);
        $magic->expects(self::exactly(2))->method('setFillColor')->withConsecutive(['red'], [$green]);

        $draw = new Draw($magic);
        $draw->withFillColor('red', function ($d) use ($draw) {
            $this->assertSame($draw, $d);
        });
    }

    public function testWithStrokeColor(): void
    {
        $green = $this->getMockBuilder(ImagickPixel::class)->disableOriginalConstructor()->getMock();
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();
        $magic->expects(self::once())->method('getStrokeColor')->willReturn($green);
        $magic->expects(self::exactly(2))->method('setStrokeColor')->withConsecutive(['red'], [$green]);

        $draw = new Draw($magic);
        $draw->withStrokeColor('red', function ($d) use ($draw) {
            $this->assertSame($draw, $d);
        });
    }

    public function testShiftBy(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();

        $draw = new Draw($magic);
        $shifted = $draw->shiftBy(new Point(1, 2), new Point(10, 20));
        $this->assertEquals(new Point(11, 22), $shifted);
    }

    public function testShiftAllBy(): void
    {
        $magic = $this->getMockBuilder(ImagickDraw::class)->disableOriginalConstructor()->getMock();

        $draw = new Draw($magic);
        $shifted = $draw->shiftAllBy(new Point(1, 2), [new Point(10, 20), new Point(40, 90)]);
        $this->assertEquals([new Point(11, 22), new Point(41, 92)], $shifted);
    }
}
