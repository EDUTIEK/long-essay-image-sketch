<?php

declare(strict_types=1);

namespace LongEssayImageSketch\ImageMagick;

use Imagick;
use ImagickDraw;
use LongEssayImageSketch\Sketch as SketchInterface;
use Exception;

class Sketch implements SketchInterface
{
    private string $output_format;

    public function __construct(string $output_format = 'PNG')
    {
        $this->assertSupportedFormat($output_format);
        $this->output_format = $output_format;
    }

    public function applyShapes(array $shapes, $image)
    {
        $magic = new Imagick();
        $magic->readImageFile($image);

        foreach ($shapes as $shape) {
            $draw = new ImagickDraw();
            $draw->setFillColor('#00000000');
            $shape->draw(new Draw($draw));
            $magic->drawImage($draw);
        }

        return $this->asStream($magic);
    }

    private function asStream(Imagick $magic)
    {
        $fd = fopen('php://temp', 'w+');
        $magic->writeImageFile($fd, $this->output_format);
        rewind($fd);

        return $fd;
    }

    private function assertSupportedFormat(string $format): void
    {
        if (!in_array($format, (new Imagick())->queryFormats(), true)) {
            throw new Exception('Image format "' . $format . '" is not supported by image magick.');
        }
    }
}
