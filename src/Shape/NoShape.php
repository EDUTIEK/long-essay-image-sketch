<?php

declare(strict_types=1);

namespace LongEssayImageSketch\Shape;

use LongEssayImageSketch\Shape;
use LongEssayImageSketch\Point;
use LongEssayImageSketch\Draw;

abstract class NoShape implements Shape
{
    protected Point $pos;
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
        if (!empty($this->label)) {
            $draw->withFillColor('white', function (Draw $draw): void {
                if ($this instanceof Circle) {
                    $y = $this->pos->y() - 120;
                }
                else {
                    $y = $this->pos->y() - 20;
                }
                
                $draw->text(New Point($this->pos()->x(), $y), ' ' . $this->label() . ' ', '#808080');
            });
        }
    }
}
