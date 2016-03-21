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
if(isset($_POST["campo_opciones_pro"]) && !empty($_POST["campo_opciones_pro"]) && isset($_POST["lista_preguntas_pro"]) && !empty($_POST["lista_preguntas_pro"])) {
	echo agregarOpcion($_POST["campo_opciones_pro"], $_POST["lista_preguntas_pro"]); }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); } 

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>