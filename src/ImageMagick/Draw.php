<?php

declare(strict_types=1);

namespace LongEssayImageSketch\ImageMagick;

use LongEssayImageSketch\Draw as DrawInterface;
use ImagickDraw;
use LongEssayImageSketch\Point;
use Closure;

/**
 * @implements DrawInterface<ImagickDraw>
 */
class Draw implements DrawInterface
{
    private ImagickDraw $magic;

    public function __construct(ImagickDraw $magic)
    {
        $this->magic = $magic;
    }

    public function path(Point $start, array $segments): void
    {
        $this->withPath(function() use ($start, $segments) {
            $this->magic->pathMoveToAbsolute($start->x(), $start->y());
            foreach ($segments as $segment) {
                $segment($this->magic);
            }
        });
    }

    public function lineTo(Point $point): Closure
    {
        return function (ImagickDraw $magic) use ($point): void {
            $magic->pathLineToAbsolute($point->x(), $point->y());
        };
    }

    public function quadraticCurve(Point $control, Point $point): Closure
    {
        return function (ImagickDraw $magic) use ($control, $point): void {
            $magic->pathCurveToQuadraticBezierAbsolute(
                $point->x() + $control->x(),
                $point->y() + $control->y(),
                $point->x(),
                $point->y()
            );
        };
    }

    public function withRotation(float $angle, callable $within): void
    {
        $degrees = $angle * 180 / pi();
        $this->magic->rotate($degrees);
        $within($this);
        $this->magic->rotate(-$degrees);
    }


    public function polygon(array $points): void
    {
        if ([] === $points) {
            return;
        }
        $this->path($points[0], array_map([$this, 'lineTo'], array_slice($points, 1)));
    }

    public function circle(Point $center, float $radius): void
    {
        $this->magic->circle($center->x(), $center->y(), $center->x() + $radius, $center->y() + $radius);
    }

    public function withFillColor(string $color, callable $within): void
    {
        $old = $this->magic->getFillColor();
        $this->magic->setFillColor($color);
        $within($this);
        $this->magic->setFillColor($old);
    }

    public function withStrokeColor(string $color, callable $within): void
    {
        $old = $this->magic->getStrokeColor();
        $this->magic->setStrokeColor($color);
        $within($this);
        $this->magic->setStrokeColor($old);
    }

    public function shiftBy(Point $by, Point $point): Point
    {
        return new Point($by->x() + $point->x(), $by->y() + $point->y());
    }

    public function shiftAllBy(Point $by, array $points): array
    {
        return array_map(function($p) use ($by) {return $this->shiftBy($by, $p);}, $points);
    }

    private function withPath(callable $proc): void
    {
        $this->magic->pathStart();
        $proc();
        $this->magic->pathFinish();
    }
}
