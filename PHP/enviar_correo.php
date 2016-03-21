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
if(isset($_POST["correo"]) && !empty($_POST["correo"])) {
	echo enviarCorreo($_POST["correo"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Correo invalido")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>