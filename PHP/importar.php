<?php

//******************************************** Configuración **********************************************\\
/*
	Importa SDK PARSE.
	Inicia las variables de sesión.
	Delega al SDK de PARSE el manejo de sesiones.
	Inicializa SDK de PARSe con las credenciales.
*/
require_once("VENDOR/AUTOLOAD.php");
session_start();
use Parse\ParseClient;
use Parse\ParseSessionStorage;
ParseClient::setStorage(new ParseSessionStorage());
ParseClient::initialize("UxFAoz2RuPuJ80HmayB7HwUBEzC93ug0gg1wgX89", "0aRv8DVqghkjONJnup6m8JdNKDcGsR809NyIDDQE", "StjmeTRscffiQKEy765ARKkmp6CdLi033nmbsxJ3");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>