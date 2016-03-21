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
if(isset($_POST["campo_correo"]) && !empty($_POST["campo_correo"])) {
	if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST["campo_correo"])) {
		echo recuperarPassword($_POST["campo_correo"]); }
	else { echo imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Corro invalido")); } }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>