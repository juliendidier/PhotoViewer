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

// definitions
$app['debug'] = true;

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

$app->get('/image/thumbnail', function (Request $request) {
    $path = $request->query->get('path');
    $pathInfo = pathinfo($path);

    $image = file_get_contents($path);
    if ($image) {
        return new Response($image, 200, [
            'Content-Type' => 'image/'.$pathInfo['extension']
        ]);
    }
})->bind('image_thumbnail');

$app->run();
