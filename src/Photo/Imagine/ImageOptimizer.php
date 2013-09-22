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
        $image = $this->prepare($stream);

        return $this->get($image->image, $extension);
    }

    public function thumbnail($stream, $extension, BoxInterface $size)
    {
        $config = $this->config['size']['thumbnail'];
        $image = $this->resize($stream, [
            'width' => $size->getWidth(), 'height' => $size->getHeight()
        ], $extension, false);

        $image = $this->prepare($image, $extension);
        $x = (int) ($image->box->getWidth() - $size->getWidth()) / 2;
        $y = (int) ($image->box->getHeight() - $size->getHeight()) / 2;

        $point = new Point($x, $y);

        return $image->image->crop($point, $size);
    }

    public function resize($stream, $limit, $extension, $remake = true)
    {
        $image = $this->prepare($stream);
        $updated = false;
        if (null !== $limit['height'] && $limit['height'] < $image->box->getHeight()) {
            $size = $image->box->heighten($limit['height']);
            $update = true;
        }

        if (null !== $limit['width'] && $limit['width'] < $image->box->getWidth() && $remake) {
            $size = $image->box->widen($limit['width']);
            $update = true;
        }

        if (!$updated) {
            return $stream;
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
        $image = $imagine->load($stream);
        $size = $image->getSize();

        return new Image($image, $size);
    }
}
