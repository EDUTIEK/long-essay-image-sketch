<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Draw;

class Circle extends NoShape
{
    public function draw(Draw $draw): void
    {
        $draw->withFillColor($this->color(), function ($draw) {
            $draw->circle($this->pos(), 5);
        });
    }
}
