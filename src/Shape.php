<?php

declare(strict_types=1);

namespace LongEssayImageSketch;

/**
 * @template A
 */
interface Shape
{
    /**
     * @param Draw<A> $draw
     */
    public function draw(Draw $draw): void;
    public function pos(): Point;
    public function label(): string;
    public function color(): string;
}
