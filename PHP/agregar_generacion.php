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
if(isset($_POST["campo_periodo_generacion"]) && !empty($_POST["campo_periodo_generacion"])) {
	echo agregarGeneracion($_POST["campo_periodo_generacion"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); } 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>