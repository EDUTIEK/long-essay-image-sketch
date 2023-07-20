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
            $draw->polygon([$this->pos(), $draw->shiftBy($this->pos(), $this->end)]);
        });
    }
}
