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


//Ruta de demostración, se recibe(n) dato(s) y se manipulan
$app->post('/guardardato', function (Request $request) use ($app) {
   
   $voltaje = $request->get('voltaje');
   $corriente = $request->get('corriente');
   $motorsense=$motorsense->get('motorsense')
  	

$dbconn = pg_pconnect("host=ec2-35-169-92-231.compute-1.amazonaws.com port=5432 dbname=d40d9mehlild8g user=wsslccaolqixxt password=7809ae03fd8da52449097500903b66b89591dfa44e9fecfb9100605a0eb7b1c1");

$data= array(
		"fecha"=>date('Y-m-d H:i:s'),
		"corriente"=> $corriente
		"voltaje"=> $voltaje
		"motorsense" $motorsense
		
);
$respuesta = pg_insert($dbconn, "Motor_view", $data);
return $respuesta;

  $nombre = $request->get('nombre');
  $respuesta = "Hola " .$nombre;
    return $respuesta;
});

$app->run();