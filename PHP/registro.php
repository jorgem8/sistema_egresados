<?php

//******************************************** Configuración **********************************************\\
/*
	Importa archivo que contiene la funciones basicas del sistema.
*/
require_once("funciones.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//********************************************* Verificacion ***********************************************\\
/*
	Verifica que se hayan enviado correctamente los parametros POST y que los campos sean validos.
*/
if(isset($_POST["campo_correo"]) && isset($_POST["campo_numero_control"]) && isset($_POST["campo_contraseña"]) && isset($_POST["campo_confirmar_contraseña"]) && !empty($_POST["campo_correo"]) && !empty($_POST["campo_numero_control"]) && !empty($_POST["campo_contraseña"]) && !empty($_POST["campo_confirmar_contraseña"])) {
	if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST["campo_correo"]) && preg_match("/^[0-9]+$/" , $_POST["campo_numero_control"]) && strlen($_POST["campo_numero_control"]) > 7) {
		 if(strlen($_POST["campo_contraseña"]) > 7) {
		 	if(preg_match("/^[0-9a-zA-Z]+$/", $_POST["campo_contraseña"])) {
		 		if($_POST["campo_contraseña"] == $_POST["campo_confirmar_contraseña"]) { 
		 			echo registro($_POST["campo_numero_control"], $_POST["campo_contraseña"], $_POST["campo_correo"]); }
				else { echo imprimirMensaje(5, array("estatus" => "error", "mensaje" => "Contraseñas no coinciden")); } }
			else { echo imprimirMensaje(4, array("estatus" => "error", "mensaje" => "Contraseña solo puede contener numeros y letras")); } }
		else { echo imprimirMensaje(3, array("estatus" => "error", "mensaje" => "Contraseña debe contener al menos 8 caracteres")); } }
	else { echo imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Datos invalidos")); } }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>