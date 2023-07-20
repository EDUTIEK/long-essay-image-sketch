<?php

declare(strict_types=1);

namespace LongEssayImageSketch;

interface Sketch
{
    /**
     * Draws the shapes onto a copy of the image.
     *
     * @param Shape[] $shapes
     * @param resource $image
     * @return resource
     */
    public function applyShapes(array $shapes, $image);
}
