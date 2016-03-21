<?php

//******************************************** Configuración **********************************************\\
/*
	Configura el idioma de la pagina e importa archivo que contiene la funciones basicas del sistema.
*/
setlocale(LC_ALL,"es_ES");
require_once("PHP/funciones.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//******************************************** Verifica sesión *********************************************\\
/*
	Redirige al panel de usuario si existe una sesión activa.
*/
$usuario_actual = obtenerUsuarioActual();
if($usuario_actual) { header("Location:HOME.php"); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta charset="utf-8">
	    <meta content="initial-scale=1.0,width=device-width" name="viewport"/>
	    <title>Egresados ITT</title>
	    <link href="CSS/MATERIALIZE.css" media="projection,screen" rel="stylesheet" type="text/css"/>
	    <link href="CSS/INDEX.css" media="projection,screen" rel="stylesheet" type="text/css"/>
	    <link href="http://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
	    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>
	<body>
		<header>
		    <nav class="top-nav white">
		      <div class="container">
		        <div class="nav-wrapper valign-wrapper">
		          <ul class="left">
		            <li class="white"><a><i class="grey-text text-darken-3 material-icons">class</i></a></li>
		          </ul>
		          <a class="grey-text text-darken-2">INSTITUTO NACIONAL DE MÉXICO</a>
		        </div>
		      </div>
		    </nav>
		</header>
		<main>
	    	<div class="center container">
				<h2 class="thin white-text">Sistema para la gestión de egresados</h2>
				<a class="blue lighten-1 btn thin" href="LOGIN.php">Ingresar</a>
		    </div>
  		</main>
  		<footer>
  			<div class="teal darken-1 footer-copyright valign-wrapper">
  				<div class="container">
			        <p class="right thin white-text">
			        	<a class="white-text" href="http://tectijuana.edu.mx/">Instituto Nacional de México</a> 
			        	- Derechos reservados ©2016
			        </p>
		      	</div>
		    </div>
		</footer>
	</body>
</html>