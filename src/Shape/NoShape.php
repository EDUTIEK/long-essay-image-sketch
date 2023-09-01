<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Shape;
use LongEssayImageSketch\Point;
use LongEssayImageSketch\Draw;

abstract class NoShape implements Shape
{
    private Point $pos;
    private string $label;
    private string $color;

    public function __construct(Point $pos, string $label, string $color)
    {
        $this->pos = $pos;
        $this->label = $label;
        $this->color = $color;
    }

    public function pos(): Point
    {
        return $this->pos;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function color(): string
    {
        return $this->color;
    }

    protected function drawLabel(Draw $draw): void
    {
        $draw->withFillColor('white', function (Draw $draw): void {
            $draw->text(New Point($this->pos()->x(), $this->pos->y()-20), ' ' . $this->label() . ' ', '#808080');
        });
    }
}
