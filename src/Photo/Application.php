<?php

namespace Photo;

use Silex\Application as SilexApplication;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Application extends SilexApplication
{
    use \Silex\Application\TwigTrait;

    public function __construct()
    {
        parent::__construct();

        $finder = new Finder();

        $iterator = $finder
            ->files()
            ->name('*.yml')
            ->in(__DIR__.'/../../config')
        ;

        $this['config'] = array();
        foreach ($iterator as $file) {
            $yaml = new Yaml();
            $config = $yaml->parse($file->getRealPath());
            $this['config'] = array_merge($this['config'], $config);
        }
    }
}
