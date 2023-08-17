<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Draw;
use LongEssayImageSketch\Point;

class Polygon extends NoShape
{
    /** @var list<Point> */
    private array $polygon;

    /**
     * @param list<Point> $polygon
     */
    public function __construct(array $polygon, ...$args)
    {
        $this->polygon = $polygon;
        parent::__construct(...$args);
    }

    public function draw(Draw $draw): void
    {
        $draw->withFillColor($this->color(), function ($draw) {
            $draw->polygon($draw->shiftAllBy($this->pos(), $this->polygon));
        });
        $this->drawLabel($draw);
    }
}
