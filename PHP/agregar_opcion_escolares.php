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
if(isset($_POST["campo_opciones_esc"]) && !empty($_POST["campo_opciones_esc"]) && isset($_POST["lista_preguntas_esc"]) && !empty($_POST["lista_preguntas_esc"])) {
	echo agregarOpcion($_POST["campo_opciones_esc"], $_POST["lista_preguntas_esc"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); } 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>