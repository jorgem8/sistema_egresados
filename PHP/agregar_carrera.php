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
if(isset($_POST["campo_nombre_carrera"]) && !empty($_POST["campo_nombre_carrera"])) { 
	echo agregarCarrera($_POST["campo_nombre_carrera"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>