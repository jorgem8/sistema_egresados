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
if(isset($_POST["lista_idioma"])  && isset($_POST["campo_empresa"])  && !empty($_POST["lista_idioma"])) {
	if($_POST["lista_idioma"] == "Ingles" || $_POST["lista_idioma"] == "Aleman" || $_POST["lista_idioma"] == "Mandarin" || $_POST["lista_idioma"] == "Ninguno") {
		echo datosProfesionales($_POST["lista_idioma"], $_POST["campo_empresa"]); }
	else { echo imprimirMensaje(2, array("estatus" => "error", "mensaje" => "Idioma invalido")); } }
else { echo imprimirMensaje(1, array("estatus" => "error", "mensaje" => "Campos incompletos")); }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>