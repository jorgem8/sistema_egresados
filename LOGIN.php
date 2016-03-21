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
									<li class="col tab s3"><a class="white-text" href="#ingresar">Acceso</a></li>
									<li class="col tab s3"><a class="white-text" href="#registro">Registro</a></li>
									<div class="indicator white" style="z-index:1"></div>
							  	</ul>
							</div>


							<div class="card-content">


								<div id="ingresar">
									<form id="formulario_entrar">
										<div class="row">
											<div class="col input-field s12">
										  		<i class="material-icons prefix">featured_play_list</i>
										  		<input id="campo_numero_control" name="campo_numero_control" onkeypress="btnEnterClick(event, 'iniciar_sesion','formulario_entrar','precargador_entrar','boton_entrar')" type="text"/>
										  		<label for="icon_prefix">Numero de control</label>
											</div>
									  	</div>
									  	<div class="row">
											<div class="col input-field s12">
										  		<i class="material-icons prefix">lock</i>
											  	<input class="TXTLOGIN" id="campo_contraseña" name="campo_contraseña" onkeypress="btnEnterClick(event, 'iniciar_sesion','formulario_entrar','precargador_entrar','boton_entrar')" type="password"/>
											  	<label for="icon_prefix">Contraseña</label>
											</div>
											<div class="col s12">
									  			<a href="PASSWORD.php" class="right light black-text" style="font-size:smaller;">Olvidaste tu contraseña?</a>
											</div>
								  		</div><br><br>
									  	<div class="row">
											<div class="active preloader-wrapper right small" id="precargador_entrar">
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
											
											<div class="col s12" id="boton_entrar">
										  		<a class="blue right btn lighten-1 thin" onclick="enviarFormulario('iniciar_sesion','formulario_entrar','precargador_entrar','boton_entrar');">Ingresar</a>
											</div>
									  	</div>
									</form>
			  					</div>





			  					<div id="registro">
			  						<form id="formulario_registro">
										<div class="nmb row">
											<div class="col input-field s12">
												<i class="material-icons prefix">featured_play_list</i>
											  	<input id="campo_numero_control" name="campo_numero_control" onkeypress="btnEnterClick(event,'registro','formulario_registro','precargador_registro','boton_registro')" type="text"/>
											  	<label for="icon_prefix">Numero de control</label>
											</div>
									  	</div>
									  	<div class="nmb row">
											<div class="col input-field s12">
												<i class="material-icons prefix">contact_mail</i>
											  	<input id="campo_correo" name="campo_correo" onkeypress="btnEnterClick(event,'registro','formulario_registro','precargador_registro','boton_registro')" type="text"/>
											  	<label for="icon_prefix">Correo electrónico</label>
											</div>
									  	</div>
									  	<div class="nmb row">
									  		<div class="col input-field s12">
									  			<i class="material-icons prefix">lock</i>
											  	<input id="campo_contraseña" name="campo_contraseña" onkeypress="btnEnterClick(event,'registro','formulario_registro','precargador_registro','boton_registro')" type="password"/>
										  		<label for="icon_prefix">Contraseña</label>
											</div>
									  	</div>
									  	<div class="row">
											<div class="col input-field s12">
											  	<i class="material-icons prefix"></i>
										  		<input id="campo_confirmar_contraseña" name="campo_confirmar_contraseña" onkeypress="btnEnterClick(event,'registro','formulario_registro','precargador_registro','boton_registro')" type="password"/>
										  		<label for="icon_prefix">Confirmar contraseña</label>
											</div>
									  	</div>
									  	<div class="right row">
									  		<div class="active preloader-wrapper small" id="precargador_registro">
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
											<div class="col s12" id="boton_registro">
										  		<a class="blue btn lighten-1 thin" onclick="enviarFormulario('registro','formulario_registro','precargador_registro','boton_registro');">Registrar</a>
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