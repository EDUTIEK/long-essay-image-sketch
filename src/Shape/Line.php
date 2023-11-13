<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Draw;
use LongEssayImageSketch\Point;

class Line extends NoShape
{
    private Point $end;

    public function __construct(Point $end, ...$args)
    {
        $this->end = $end;
        parent::__construct(...$args);
    }

    public function draw(Draw $draw): void
    {
        $draw->withFillColor($this->color(), function ($draw) {
            $draw->polygon($draw->shiftAllBy($this->pos(), [
                new Point(0, -10),
                new Point($this->end->x(), -10),
                new Point($this->end->x(), 0),
                new Point(0, 0),
            ]));
        });
        $this->drawLabel($draw);
    }
}
