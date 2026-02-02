<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Draw;
use LongEssayImageSketch\Point;

class Line extends NoShape
{
    private Point $end;

    public const LINE_WIDTH = 6;

    public function __construct(Point $end, ...$args)
    {
        $this->end = $end;
        parent::__construct(...$args);
    }

    public function draw(Draw $draw): void
    {
        $draw->with([
            'strokeColor' => $this->color(),
            'strokeWidth' => self::LINE_WIDTH,
        ], fn () => $draw->polygon([$this->pos(), $draw->shiftBy($this->pos(), $this->end)]));

        $this->drawLabel($draw);
    }
}
