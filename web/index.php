<?php
require('../vendor/autoload.php');
use Symfony\Component\HttpFoundation\Request;
$app = new Silex\Application();
$app['debug'] = true;
// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));
// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});
$app->get('/ruta', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/modificarDato', function (Request $request) use ($app) {
$app->post('/Datoenviado', function (Request $request) use ($app) {
    $nombre = $request->get('nombre');
  $respuesta = "Hola " .$nombre;
  $respuesta = "Esp32ok" .$nombre;
    return $respuesta;
});