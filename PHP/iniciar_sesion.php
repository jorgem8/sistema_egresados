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
if(isset($_POST["campo_numero_control"]) && isset($_POST["campo_contraseña"]) && !empty($_POST["campo_numero_control"]) && !empty($_POST["campo_contraseña"])) {
	echo iniciarSesion($_POST["campo_numero_control"], $_POST["campo_contraseña"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>