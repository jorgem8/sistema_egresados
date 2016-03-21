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
if(isset($_POST["campo_correo"]) && isset($_POST["campo_nombres"]) && isset($_POST["campo_apellidos"]) && isset($_POST["campo_telefono"]) && isset($_POST["campo_curp"]) && isset($_POST["campo_fecha_nacimiento"]) && !empty($_POST["campo_correo"]) && !empty($_POST["campo_nombres"]) && !empty($_POST["campo_apellidos"]) && !empty($_POST["campo_telefono"]) && !empty($_POST["campo_curp"]) && !empty($_POST["campo_fecha_nacimiento"])) {
	if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST["campo_correo"])) {
		if(preg_match("/^[0-9]+$/", $_POST["campo_telefono"])) { 
			$fecha = new DateTime($_POST["campo_fecha_nacimiento"]);
			echo datosPersonales($_POST["campo_correo"], $_POST["campo_nombres"], $_POST["campo_apellidos"], $_POST["campo_telefono"], $_POST["campo_curp"], $fecha -> format('Y-m-d H:i:s')); }
		else { echo imprimirMensaje(3, array("estatus" => "error", "mensaje" => "Telefono invalido")); } }
	else { echo imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Correo invalido")); } }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>