<?php

//******************************************** Configuración **********************************************\\
/*
	Importa archivo que contiene la funciones basicas del sistema.
*/
require_once("funciones.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//********************************************* Verificacion ***********************************************\\
/*
	Verifica que se hayan enviado correctamente los parametros POST.
*/
if(isset($_POST["clase_objeto"]) && !empty($_POST["clase_objeto"]) && isset($_POST["id"]) && !empty($_POST["id"])) {
	echo eliminarObjeto($_POST["clase_objeto"], $_POST["id"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); } 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>