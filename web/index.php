<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Photo\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());


$app->get('/', function (Request $request) use ($app) {
    $path = urldecode($request->query->get('path', ''));
    $basePath = $app['config']['path'].$path;

    $finder = new Finder();

    $dirs = $finder
        ->directories()
        ->depth(0)
        ->in($basePath)
        ->getIterator()
    ;

    $finder = new Finder();
    $extensions = '*.('.implode('|', $app['config']['extensions']).')';
    foreach ($app['config']['extensions'] as $extension) {
        $finder->name('*.'.$extension);
    }

    $files = $finder
        ->files()
        ->depth(0)
        ->in($basePath)
        ->sortByModifiedTime()
        ->getIterator()
    ;

    return $app->render('index.html.twig', [
        'breadcrumb' => explode('/', $path),
        'path' => $path,
        'dirs' => $dirs,
        'files' => $files]
    );
})->bind('homepage');

$app->get('/thumbnail', function (Request $request) use ($app) {
    $optimizer = new \Photo\Imagine\ImageOptimizer($app['config']['image_optimizer']);

    $config = $app['config']['image_optimizer']['size']['thumbnail'];
    $path = urldecode($request->query->get('path', ''));
    $width = $request->query->get('width', $config['width']);
    $height = $request->query->get('height', $config['height']);
    $basePath = $app['config']['path'].$path;
    $pathInfo = pathinfo($path);

    $image = file_get_contents($basePath);
    $size = new \Imagine\Image\Box($width, $height);
    $image = $optimizer->thumbnail($image, $pathInfo['extension'], $size);

    if ($image) {
        return new Response($image, 200, [
            'Content-Type' => 'image/'.$pathInfo['extension']
        ]);
    }
})->bind('thumbnail');

$app->get('/image', function (Request $request) use ($app) {
    $optimizer = new \Photo\Imagine\ImageOptimizer($app['config']['image_optimizer']);

    $path = urldecode($request->query->get('path', ''));
    $width = $request->query->get('width', $app['config']['image_optimizer']['size']['image']['width']);
    $height = $request->query->get('height', $app['config']['image_optimizer']['size']['image']['height']);

    $basePath = $app['config']['path'].$path;
    $pathInfo = pathinfo($path);

    $image = file_get_contents($basePath);
    $size = new \Imagine\Image\Box($width, $height);

    $image = $optimizer->resize($image, $size, $pathInfo['extension']);

    if ($image) {
        return new Response($image, 200, [
            'Content-Type' => 'image/'.$pathInfo['extension']
        ]);
    }
})->bind('image');


$app->run();
