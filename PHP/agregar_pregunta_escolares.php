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
if(isset($_POST["campo_preguntas_esc"]) && !empty($_POST["campo_preguntas_esc"])) {
	echo agregarPregunta($_POST["campo_preguntas_esc"], "1"); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); } 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>