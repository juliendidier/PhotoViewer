<?php

namespace Photo\Imagine;

use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\Point;

class ImageOptimizer
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function optimize($stream, $extension)
    {
        list($image, $size) = $this->prepare($stream);

        return $this->get($image, $extension);
    }

    public function thumbnail($stream, $extension, BoxInterface $size)
    {
        $config = $this->config['size']['thumbnail'];
        $image = $this->resize($stream, [
            'width' => $size->getWidth(), 'height' => $size->getHeight()
        ], $extension, false);

        list($image, $box) = $this->prepare($image, $extension);
        $x = (int) ($box->getWidth() - $size->getWidth()) / 2;
        $y = (int) ($box->getHeight() - $size->getHeight()) / 2;

        $point = new Point($x, $y);
        return $image->crop($point, $size);
    }

    public function resize($stream, $limit, $extension, $remake = true)
    {
        list($image, $size) = $this->prepare($stream);

        if (null !== $limit['height'] && $limit['height'] < $size->getHeight()) {
            $size = $size->heighten($limit['height']);
        }

        if (null !== $limit['width'] && $limit['width'] < $size->getWidth() && $remake) {
            $size = $size->widen($limit['width']);
        }

        return $image->resize($size)->get($extension);
    }

    public function get(ImageInterface $image, $extension)
    {
        $config['quality'] = $this->config['quality'];

        return $image->get($extension);
    }

    protected function prepare($stream)
    {
        $imagine = new Imagine();
        $image   = $imagine->load($stream);
        $size    = $image->getSize();

        return array($image, $size);
    }
}
