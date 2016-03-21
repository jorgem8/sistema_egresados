<?php

//********************************* Sistema para seguimiento de egresados *********************************\\
/*
	Este archivo contiene las funciones basicas para la consulta, inserción, eliminacion y actualización
	de los datos de los egresados del Instituto Tecnológico de Tijuana.
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//*************************************** Conexión a base de datos ****************************************\\
/*
	Realiza la configuración de la conexion a la base de datos.
	- Nombre de la base de datos: seguimiento_egresados.
	- Dirección del servidor de la base de datos: 127.0.0.1.
	- Nombre de usuario del servidor de la base de datos: sistema_egresados.
	- Contraseña del servidor de la base de datos ****** :p.
	El manejo de la base de datos se hace por medio de la interfaz PDO (PHP Data Objects).
*/
$nombre_bd        = "seguimiento_egresados";
$usuario          = "sistema_egresados";
$contraseña       = "ITTTijuana";
$servidor         = "localhost";
//Crea un objeto del tipo PDO que funcionara como administrador de la conexion con la base de datos.
$administrador_bd = new PDO("mysql:dbname=" . $nombre_bd . ";host=" . $servidor . ";charset=utf8", $usuario, $contraseña);
$administrador_bd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$administrador_bd -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//*********************************** Importa archivos necesarios PARSE ************************************\\
/*
	Importa cada uno de los modulos necesarios para la conexión al servicio de almacenamiento de PARSE
	(Utilizado actualmente solo para el manejo de usuarios y sesiones).
	Ademas importa la libreria PHPMailer para el envio de correos electronicos.
*/	
require_once("PHPMAILER/autoload.php");
require_once("importar.php");
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseSession;
use Parse\ParseUser;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Funciones de usuario ******************************************\\
/*
	Funciones para la administración de los usuarios del sistema tales como:
	inicio de sesion, registro, terminar sesion y recuperacion de contraseña.
*/
function obtenerUsuarioActual() {
	//Retorna el usuario actual si existe una sesión activa, en caso contrario retorna 0.
	try { return ParseUser::getCurrentUser() ? ParseUser::getCurrentUser() : 0 ; }
	catch(ParseException $ex) { return 0; } }

function iniciarSesion($no_control, $contraseña) {
	//Genera un inicio de sesión con los datos provistos.
	//Busca el historial de acceso para el usuario que realizo el inicio de sesion
	//si este existe sera actualizado, de no ser asi se genera uno.
	//Envia respuesta al cliente en formato JSON.
	try {
		$fecha   		  = new DateTime();
		$usuario 		  = ParseUser :: logIn($no_control, $contraseña);
		$usuario 		  = ParseUser :: become($usuario -> getSessionToken());
		$historial_acceso = obtenerObjetos("HistorialAcceso", array( "=" => array("id_egresado" => "\"" . $usuario -> getObjectId() . "\"" )), 1);
		if($historial_acceso) { actualizarObjeto("HistorialAcceso", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\"", "fecha" => "\"" . $fecha -> format("Y-m-d H:i:s") . "\""), $historial_acceso["id"]); }
		else { agregarObjeto("HistorialAcceso", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\"", "fecha" => "\"" . $fecha -> format("Y-m-d H:i:s") . "\"")); }
		return imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Sesion iniciada", "destino" => "HOME.php")); }
	catch (ParseException $ex) { return imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Parametros invalidos")); } }

function registro($no_control, $contraseña, $correo) {
	//Crea y regista un nuevo usuario asignando los parametros provistos (Actualmente utilizando PARSE).
	//Genera un registro en datos personales, escolares y profesionales, asi como en historial de acceso.
	//Envia respuesta al cliente en formato JSON.
	$fecha   = new DateTime();
	$usuario = new ParseUser();
	$usuario -> set("username", $no_control);
	$usuario -> set("password", $contraseña);
	$usuario -> set("email", $correo);
	try {
		$usuario -> signUp();
		agregarObjeto("Egresado", array("id" => "\"" . $usuario -> getObjectId() . "\""));
		agregarObjeto("DatosPersonales", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\""));
		agregarObjeto("DatosProfesionales", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\""));
		agregarObjeto("DatosEscolares", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\"", "numero_control" => $no_control));
		agregarObjeto("HistorialAcceso", array("id_egresado" => "\"" . $usuario -> getObjectId() . "\"", "fecha" => "\"" . $fecha -> format('Y-m-d H:i:s') . "\""));
		session_destroy();
		ParseSession :: getCurrentSession() -> destroy();
		return imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Registro exitoso")); }
	catch (ParseException $ex) { return imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Imposible realizar el registro")); } }

function cerrarSesion() {
	//Obtiene la sesión del usuario activa y la termina.
	//Redirige al usuario a la pagina principal.
	try {
		ParseSession      :: getCurrentSession() -> destroy();
		ParseUser         :: logOut();
		session_destroy(); }
	catch(ParseException $ex) { if($ex -> getCode() == "209") { ParseUser :: logOut(); } }
	header("Location:../INDEX.php"); }

function recuperarPassword($correo) { 
	//Envia un mensake de recuperación de contraseña al correo registrado (Actualmente utilizando PARSE).
	//Envia respuesta al cliente en formato JSON.
	try {
		ParseUser :: requestPasswordReset($correo);
		return imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Te enviamos un correo de recuperación")); }
	catch(ParseException $ex) { return imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Correo invalido")); } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Funciones auxiliares ******************************************\\
/*
	Funciones auxiliares que simplifican tareas como:
	- Codificación de mensajes en formato JSON para envio de respuestas al cliente.
	- Envio de correos electronicos a usuarios registrados en el sistema.
*/
function imprimirMensaje($codigo_error, $descripcion) { 
	//Codifica un mensaje en formato JSON con los campos "codigo_error" y "descripcion".
	//Ejemplo de uso: imprimirMensaje(0, array("clave" => "valor"));
	//Resultado: { "codigo_error" : 0 , "descripcion" : { "clave" : "valor" } }
	return json_encode(array("codigo_error" => $codigo_error, "descripcion" => $descripcion)); }

function enviarCorreo($correo_electronico) {
	//Envia un correo electrónico al correo provisto como parametro (Utilizando PHPMailer).
	$correo = new PHPMailer();
	$correo -> IsSMTP();
	$correo -> SMTPAuth   = true;
	$correo -> SMTPSecure = "tls";
	$correo -> Host 	  = "smtp.gmail.com";
	$correo -> Port 	  = 587;
	$correo -> Password   = "ITTTijuana";
	$correo -> FromName   = "Sistema egresados";
	$correo -> Subject 	  = "Sistema de egresados";
	$correo -> Username   = "sistemaegresadositt@gmail.com";
	$correo -> AltBody 	  = "Por favor actualiza tus datos en el sistema de egresados del Instituto Nacional de México.";
	$correo -> IsHTML(true);
	$correo -> addAddress($correo_electronico);
	$correo -> setFrom("sistemaegresadositt@gmail.com");
	$correo -> MsgHTML("Por favor actualiza tus datos en el sistema de egresados del Instituto Nacional de México.");
	return $correo -> send() ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Correo enviado")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Error al enviar correo")); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//*************************************** Funciones de base de datos ***************************************\\
/*
	Funciones para llevar a cabo tareas en la base de datos, incluye generación dinamica de querys
	para realizar consultas, inserciones y eliminaciiones, y ejecución de sentencias preparadas.
*/
function ejecutarQuery ($query) {
	//Genera una sentencia preparada con el query(texto) provisto y la ejecuta por medio
	//del administrados de conexion de la base de datos.
	//posibles valores de retorno: id(si hubo insercion), objeto PDO(si hubo rows afectadas), 
	//1(si no hubo rows afectadas) y 0(si fallo ejecución).
    global $administrador_bd;
    $sentencia = $administrador_bd -> prepare($query);
    try {
    	$sentencia -> execute();
    	$id 	   = $administrador_bd -> lastInsertId();
    	return $sentencia -> rowCount() > 0 ? ($id ? $id : $sentencia) : 1; }
    catch(PDOException $ex) { return 0; } }

function asignarParametrosQuery($query,  $especificaciones) {
	//Agrega parametros provistos por medio de un arreglo a query de consulta y retorna el query resultante.
	//especificaciones debe ser un arreglo multidimensional.
	//Ejemplo de uso: asignarParametrosQuery("SELECT * FROM Tabla", array("=", array("clave" => "\"valor\"", "clave2" => "\"valor\"")));
	//Resultado: "SELECT * FROM Tabla WHERE clave="valor" AND clave2="valor""
	$query .= " WHERE ";
	foreach($especificaciones as $operador => $parametros) {
		foreach ($parametros as $clave => $valor ) { $query .= $clave . $operador . $valor . " AND "; } }
	return rtrim($query, " AND "); }

function obtenerObjetos($clase, $especificaciones = null, $cantidad = null) {
	//Genera un query de consulta con parametros(comparación y limite de resultados) en caso de que existan.
	//Ejecuta el query generado y retorna los registros obtenidos.
	//Posibles valores de retorno: registro(query limitado a 1 registro), arreglo de registros(query sin limite), 0(No se encontro ningun registro).
	//Ejemplo de uso: obtenerObjetos("Tabla", array("=", array("clave" => "\"valor\"", "clave2" => "\"valor\"")));
	//Resultado: "SELECT * FROM Tabla WHERE clave="valor" AND clave2="valor""
	$query     = "SELECT * FROM " . $clase;
	$query     = $especificaciones ? asignarParametrosQuery($query, $especificaciones) : $query;
	$ejecucion = $cantidad ? ejecutarQuery($query . " LIMIT " . $cantidad) : ejecutarQuery($query);
    return $ejecucion && !is_integer($ejecucion) ? ($cantidad == 1 ? $ejecucion -> fetchAll(PDO::FETCH_ASSOC)[0] : $ejecucion -> fetchAll(PDO::FETCH_ASSOC)) : 0; }

function agregarObjeto($clase, $parametros) {
	//Genera un query de inserción con los parametros provistos por medio de un arreglo.
	//Ejecuta el query generado y retorna el id del registro insertado o 0 en caso de error.
	//Ejemplo de uso: agregarObjeto("Tabla", array("clave" => "\"valor\"", "clave2" => "\"valor\""));
	//Resultado: "INSERT INTO TABLA(clave,clave2) VALUES("valor","valor")"
	global $administrador_bd;
	$query     = "INSERT INTO " . $clase;
	$claves    = ""; 
	$valores   = "";
	foreach ($parametros as $clave => $valor ) { $claves .= $clave . ","; $valores .= $valor . ","; }
	$query     .= "(" . rtrim($claves, ",") . ") VALUES(" . rtrim($valores, ",") . ")";
	$ejecucion = ejecutarQuery($query);
	return $ejecucion ? $ejecucion : 0; }

function eliminarObjeto ($clase, $id) {
	//Genera un query de eliminación para la clase y id provistos.
	//Ejecuta el query y envia resultado de la ejecución al cliente en formato JSON.
	$query     = "DELETE FROM " . $clase . " WHERE id = " . $id;
	$ejecucion = ejecutarQuery($query);
	return $ejecucion ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => $clase . " eliminada")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Error al eliminar")); }

function actualizarObjeto($clase, $parametros = null, $id) {
	//Genera un query de actualizacion con los parametros provistos para la clase y id provistos.
	//Ejecuta el query y retorna el resultado de la ejecución.
	//Ejemplo de uso: actualizarObjeto("Tabla", array("clave" => "\"valor\"", "clave2" => "\"valor\""),1);
	//Resultado: "UPDATE TABLA SET clave="valor",clave2="valor" WHERE id=1"
	$query     = "UPDATE " . $clase . " SET ";
	foreach ($parametros as $clave => $valor ) { $query .= $clave . "=" . $valor . ","; }
	$query     = rtrim($query, ",") . " WHERE id=" . $id;
	$ejecucion = ejecutarQuery($query);
	return $ejecucion; }

function filtrarObjetos($objetos_filtrables, $especificaciones, $cantidad = null) {
	//Recibe un arreglo de registros y realiza un filtrado en base a las especificaciones.
	//Ejemplo de uso: filtrarObjetos(arreglo, array("==",array("clave" => "\"valor\"", "clave2" => "\"valor\"")));
	//Resultado: "arreglo con registros que cumplen las condiciones clave=valor y clave2=valor".
	$objetos_filtrados = [];
	$agregable         = true;
	foreach ($objetos_filtrables  as $objeto) {
		foreach($especificaciones as $operador => $parametros) {
			foreach ($parametros  as $clave    => $valor ) {
				$agregable = compararParametros($objeto[$clave], $valor, $operador);
				if(!$agregable) { break 2; } } }
		if($agregable) { if($cantidad) { return $objeto; } array_push($objetos_filtrados, $objeto); } }
	return count($objetos_filtrados > 0) ? $objetos_filtrados : 0; }

function compararParametros($parametro, $valor, $operador) {
	//Retorna el resultado de la comparación de parametro y valor para el operador correspondiente.
	//Ejemplo de uso: compararParametros("hola","mundo","==");
	//Resultado: false.
	switch ($operador) {
		case "==": return $parametro == $valor;  break;
		case "!=": return $parametro != $valor;  break;
		case ">" : return $parametro >  $valor;  break;
        case "<" : return $parametro <  $valor;  break;
        case ">=": return $parametro >= $valor;  break;
        case "<=": return $parametro <= $valor;  break; } }

function obtenerUsuariosParse() {
	//Obtiene una lista de los usuarios registrados en el sistema(Actualmente utilizando PARSE).
	$query = ParseUser :: query();
	try { $usuarios = $query -> find(); return $usuarios; }
	catch(ParseException $ex) { return 0; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************* Funciones para agregar objetos *************************************\\
/*
	Funciones de alto nivel para realizar inserciones a la base de datos.
	Verifica que el registro no se haya realizado anteriormente y lo genera.
	Envia el resultado al cliente en formato JSON.
*/
function agregarCarrera ($nombre_carrera) {
	$carrera = obtenerObjetos("Carrera", array("=" => array("nombre" => "\"" . $nombre_carrera . "\"")), 1) ? 0 : agregarObjeto("Carrera", array("nombre" => "\"" . $nombre_carrera . "\""));
	return $carrera ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Carrera agregada", "id" => $carrera, "clase" => "Carrera")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Carrera agregada anteriormente")); }

function agregarGeneracion($periodo) {
	$generacion = obtenerObjetos("Generacion", array("=" => array("periodo" => "\"" . $periodo . "\"")), 1) ? 0 : agregarObjeto("Generacion", array("periodo" => "\"" . $periodo . "\""));
	return $generacion ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Generación agregada", "id" => $generacion, "clase" => "Generacion")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Generación agregada anteriormente")); }

function agregarPregunta($descripcion_pregunta, $id_encuesta) {
	$encuesta = obtenerObjetos("Encuesta", array("=" => array("id" => $id_encuesta)), 1);
	if($encuesta) {
		$pregunta = obtenerObjetos("Pregunta", array("=" => array("pregunta" => "\"" . $descripcion_pregunta . "\"", "id_encuesta" => $encuesta["id"])), 1) ? 0 : agregarObjeto("Pregunta", array("pregunta" => "\"" . $descripcion_pregunta . "\"" , "id_encuesta" => $encuesta["id"]));
		return $pregunta ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Pregunta escolares agregada", "id" => $pregunta, "clase" => "Pregunta")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Pregunta agregada anteriormente")); } }

function agregarOpcion ($descripcion_opcion, $id_pregunta) {
	$pregunta = obtenerObjetos("Pregunta", array("=" => array("id" => $id_pregunta)), 1);
	if($pregunta) {
		$opcion = obtenerObjetos("Opcion", array("=" => array("opcion" => "\"" . $descripcion_opcion . "\"", "id_pregunta" => $pregunta["id"])), 1) ? 0 : agregarObjeto("Opcion", array("opcion" => "\"" . $descripcion_opcion . "\"", "id_pregunta" => $pregunta["id"]));
		return $opcion ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Opción escolares agregada", "id" => $opcion, "id_pregunta" => $pregunta["id"], "clase" => "Opcion")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Opcion agregada anteriormente")); } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************ Funciones para actualizar datos *************************************\\
/*
	Funciones para actualizar los datos (personales, escolares, profesionales) y respuestas seleccionadas
	en las encuestas disponibles para los egresados registrados.
	(Todos)
	- Obtiene los datos para el usuario con la sesión activa y sobreescribe cada campo con los parametros provistos.
	- Envia el resultado al cliente en formato JSON.leccionada para cada una de las preguntas.
	(Escolares y profesionales)
	- Obtiene las preguntas para cada encuesta disponible (Escolares y profesionales).
	- Verifica que hayan sido contestadas todas las preguntas posibles (revisando variable POST).
	- Obtiene las opciones para cada pregunta contestada.
	- Accesa a las respuestas del usuario con la sesión activa.
	- Actualiza o genera las respuestas por medio de la opción seleccionada.
*/
function datosPersonales($correo_electronico, $nombres, $apellidos, $telefono, $curp, $fecha_nacimiento) {
	ParseUser 		  :: getCurrentUser() -> set("email", $correo_electronico);
	ParseUser         :: getCurrentUser() -> save();
	$datos_personales = obtenerObjetos("DatosPersonales", array("=" => array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"")), 1);
	$resultado        = actualizarObjeto("DatosPersonales", array("correo_electronico" => "\"" . $correo_electronico . "\"", "nombres" => "\"" . $nombres . "\"", "apellidos" => "\"" . $apellidos . "\"", "telefono" => "\"" . $telefono . "\"", "curp" => "\"" . $curp . "\"", "fecha_nacimiento" => "\"" . $fecha_nacimiento . "\"", "completado" => true), $datos_personales["id"]);
	return $resultado ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Datos personales guardados correctamente")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Error al guardar datos personales")); }

function datosEscolares($carrera, $generacion) {
	$carrera 			 = obtenerObjetos("Carrera", array("=" => array("id" => "\"" . $carrera . "\"")), 1);
	$generacion 		 = obtenerObjetos("Generacion", array("=" => array("id" => "\"" . $generacion . "\"")), 1);
	$datos_escolares     = obtenerObjetos("DatosEscolares", array("=" => array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"")), 1);
	$encuesta_escolares  = obtenerObjetos("Encuesta", array("=" => array("id" => "1")), 1);
	$preguntas_escolares = obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_escolares["id"])), null);
	$respuestas			 = array();
	$resultado 			 = true;
	foreach($preguntas_escolares as &$preguntaE) {
		if(isset($_POST[$preguntaE["id"]])) {
			$opcionE     = obtenerObjetos("Opcion", array("=" => array("id" => "\"" . $_POST[$preguntaE["id"]] . "\"")), 1);
			$respuesta   = obtenerObjetos("Respuesta", array("=" => array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"", "id_pregunta" => "\"" . $preguntaE["id"] . "\"")), 1);
			if(!$respuesta) { $respuesta = agregarObjeto("Respuesta", array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"" , "id_pregunta" => "\"" . $preguntaE["id"] . "\"", "id_opcion" => "\"" . $opcionE["id"] . "\"")); }
			$respuestas[] = is_array($respuesta) ? actualizarObjeto("Respuesta", array("id_egresado" =>  "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"", "id_pregunta" => "\"" . $preguntaE["id"] . "\"" , "id_opcion" => "\"" . $opcionE["id"] . "\""), $respuesta["id"]) : $respuesta; }
		else { return imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Selecciona una opcion para la pregunta: " . $preguntaE["pregunta"])); } }
	if($carrera && $generacion) {
		foreach($respuestas as &$respuesta) { $resultado = $respuesta; if(!$resultado) { break; } }
		if($resultado) { $resultado = actualizarObjeto("DatosEscolares", array("id_carrera" => $carrera["id"], "id_generacion" => $generacion["id"], "completado" => true), $datos_escolares["id"]); } }
	return $resultado ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Datos escolares guardados correctamente")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Error al guardar datos escolares")); }

function datosProfesionales($idioma, $empresa) {
	$datos_profesionales     = obtenerObjetos("DatosProfesionales", array("=" => array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"")), 1);
	$encuesta_profesionales  = obtenerObjetos("Encuesta", array("=" => array("id" => "2")), 1);
	$preguntas_profesionales = obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_profesionales["id"])), null);
	$respuestas 			 = array();
	$resultado  			 = true;
	foreach($preguntas_profesionales as &$preguntaP) {
		if(isset($_POST[$preguntaP["id"]])) {
			$opcionP         = obtenerObjetos("Opcion", array("=" => array("id" => "\"" . $_POST[$preguntaP["id"]] . "\"")), 1);
			$respuesta       = obtenerObjetos("Respuesta", array("=" => array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"", "id_pregunta" => "\"" . $preguntaP["id"] . "\"")), 1);
			if(!$respuesta) { $respuesta = agregarObjeto("Respuesta", array("id_egresado" => "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"" , "id_pregunta" => "\"" . $preguntaP["id"] . "\"", "id_opcion" => "\"" . $opcionP["id"] . "\"")); }
			$respuestas[]    = is_array($respuesta) ? actualizarObjeto("Respuesta", array("id_egresado" =>  "\"" . ParseUser::getCurrentUser() -> getObjectId() . "\"", "id_pregunta" => "\"" . $preguntaP["id"] . "\"" , "id_opcion" => "\"" . $opcionP["id"] . "\""), $respuesta["id"]) : $respuesta; }
		else { return imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Selecciona una opcion para la pregunta: " . $preguntaP["pregunta"])); } }
	if($resultado) {
		foreach($respuestas as &$respuesta) { $resultado = $respuesta; if(!$resultado) { break; } }
		if($resultado) { $resultado = actualizarObjeto("DatosProfesionales", array("segundo_idioma" => "\"" . $idioma . "\"", "empresa" => "\"" . $empresa . "\"", "completado" => true), $datos_profesionales["id"]); } }
	return $resultado ? imprimirMensaje(0, array("estatus" => "exito", "mensaje" => "Datos profesionales guardados correctamente")) : imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Error al guardar datos profesionales")); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>