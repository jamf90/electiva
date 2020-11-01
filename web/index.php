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
   $tabla = $request->get('tabla');
   $motor=$request->get('motor');
   $corriente =$request->get('corriente');
  
  	

$dbconn = pg_pconnect("host=ec2-35-169-92-231.compute-1.amazonaws.com port=5432 dbname=d40d9mehlild8g user=wsslccaolqixxt password=7809ae03fd8da52449097500903b66b89591dfa44e9fecfb9100605a0eb7b1c1");

$data= array(
		"fecha"=>date('Y-m-d H:i:s'),
		
		"voltaje"=> $voltaje,
		"motor"=> $motor,
		"corriente" =>$corriente 
		
		
);
$respuesta = pg_insert($dbconn, $tabla, $data);
return $respuesta;
	
//$query = "INSERT INTO " . $tabla . "(fecha,corriente,voltaje,motor) VALUES ('" . date('Y-m-d H:i:s') . "'," . $corriente . "," . $voltaje . ", '" . $motor . "');" ;
	//$respuesta = pg_query($dbconn, $query);
   	
	//echo $query; echo "<br><br>";
	//echo $respuesta; echo "<br><br>";
	//echo "ID insert: ". pg_last_oid($respuesta);
   	//return pg_last_oid($respuesta);	


});

//consulta dato 

$app->get('/consultardato', function () use ($app) {

$dbconn = pg_pconnect("host=ec2-35-169-92-231.compute-1.amazonaws.com port=5432 dbname=d40d9mehlild8g user=wsslccaolqixxt password=7809ae03fd8da52449097500903b66b89591dfa44e9fecfb9100605a0eb7b1c1");

$consulta = pg_query($dbconn, "SELECT * FROM motor_view ORDER BY id DESC LIMIT 20 ");

print_r(pg_fetch_all($consulta));

echo"<br><br>";
	
print_r(pg_fetch_array($consulta,3,PGSQL_NUM));

echo"<br><br>";
	
$cons_array = pg_fetch_array($consulta,5,PGSQL_ASSOC);
print_r($cons_array);
echo $cons_array[fecha];
	
echo"<br><br>";
$cons_object = pg_fetch_object($consulta);
print_r($cons_object);	
echo $cons_object -> fecha;
	
	
return "ok";
});
$app->get('/limpiarDatos', function () use ($app) {

	$dbconn = pg_pconnect("host=ec2-35-169-92-231.compute-1.amazonaws.com port=5432 dbname=d40d9mehlild8g user=wsslccaolqixxt password=7809ae03fd8da52449097500903b66b89591dfa44e9fecfb9100605a0eb7b1c1");

	
	$query_last = "SELECT * FROM motor_view ORDER BY id DESC LIMIT 1";
	$query_first = "SELECT * FROM motor_view ORDER BY id ASC LIMIT 1";

	$consulta_last = pg_query($dbconn, $query_last);
	$consulta_first = pg_query($dbconn, $query_first);

	$id_last = pg_fetch_result($consulta_last, null, 0);
	$id_first = pg_fetch_result($consulta_first, null, 0);

	
	$registros = $id_last - $id_first + 1;

		
		if($registros >=20){
		$id_borrar = $id_last - 10;
		$query_delete = "DELETE FROM motor_view WHERE id>=" .$id_borrar.";";
		$consulta_delete = pg_query($dbconn, $query_delete);
		return "Se borraron los registros";
	}
	else{
		return "No se borraron los registros";
	}
});


$app->run();  


