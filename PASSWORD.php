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
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Egresados ITT</title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet">
		<link href="CSS/MATERIALIZE.css" media="projection,screen" rel="stylesheet" type="text/css"/>
		<link href="CSS/LOGIN.css" media="projection,screen" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-2.0.0.js"></script>
		<script src="JS/LOGIN.js" type="text/javascript"></script>
		<script src="JS/MATERIALIZE.js" type="text/javascript" ></script>
	</head>
	<body class="grey lighten-3">
		<header>
			<nav class="white top-nav">
			  <div class="container">
				<div class="nav-wrapper valign-wrapper">
				  <ul class="left">
					<li class="white">
						<a><i class="grey-text text-darken-3 material-icons">school</i></a>
					</li>
				  </ul>
				  <a class="grey-text text-darken-2">GESTIÓN DE EGRESADOS</a>
				</div>
			  </div>
			</nav>
		</header>
		<main>
			<div class="container">
				<div class="nmb row">
					<div class="col s12 m8 l6 offset-m2 offset-l3">
						<div class="card white z-depth-2">
							<div class="card-content teal">
			  					<ul class="tabs teal">
									<li class="col tab s3"><a class="white-text" href="#recuperar">Recuperación de contraseña</a></li>
									<div class="indicator white" style="z-index:1"></div>
							  	</ul>
							</div>
							<div class="card-content">
								<br><br>
								<div id="recuperar">
									<form id="formulario_recuperar">
										<div class="row">
											<div class="col input-field s12">
										  		<i class="material-icons prefix">contact_mail</i>
										  		<input id="campo_correo" name="campo_correo" onkeypress="btnEnterClick(event, 'recuperar_password','formulario_recuperar','precargador_recuperar','boton_recuperar')" type="text"/>
										  		<label for="icon_prefix">Correo electronico</label>
											</div>
									  	</div>
								  		</div><br><br>
									  	<div class="row">
											<div class="active preloader-wrapper right small" id="precargador_recuperar">
										  		<div class="spinner-blue-only spinner-layer">
													<div class="circle-clipper left">
														<div class="circle"></div>
													</div>
													<div class="gap-patch">
														<div class="circle"></div>
													</div>
													<div class="circle-clipper right">
														<div class="circle"></div>
													</div>
										  		</div>
											</div>
											<div class="col s12" id="boton_recuperar">
										  		<a class="blue right btn lighten-1 thin" onclick="enviarFormulario('recuperar_password','formulario_recuperar','precargador_recuperar','boton_recuperar');">Recuperar</a>
											</div>
									  	</div>
									</form>
			  					</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<footer>
			<div class="darken-1 footer-copyright teal valign-wrapper">
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