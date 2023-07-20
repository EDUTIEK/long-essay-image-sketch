<?php

declare(strict_types=1);

namespace LongEssayImageSketch;

use Closure;

/**
 * @template A
 */
interface Draw
{
    /**
     * @param list<Closure(A): void>
     */
    public function path(Point $start, array $segments): void;

    /**
     * @return Closure(A): void
     */
    public function lineTo(Point $point): Closure;

    /**
     * @return Closure(A): void
     */
    public function quadraticCurve(Point $control, Point $point): Closure;

    /**
     * Parameter $angle is in radiants.
     */
    public function withRotation(float $angle, callable $within): void;
    public function polygon(array $points): void;
    public function circle(Point $center, float $radius): void;
    public function withFillColor(string $color, callable $within): void;
    public function withStrokeColor(string $color, callable $within): void;
    public function shiftBy(Point $by, Point $point): Point;
    public function shiftAllBy(Point $by, array $points): array;
}
