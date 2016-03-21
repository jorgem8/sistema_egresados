<?php

//*************************************** Administrador del sistema ***************************************\\
/*
	Este archivo contiene las funciones para la administración del sistema que permiten:
	- Registro y eliminación de carreras.
	- Registro y eliminación de generaciones.
	- Administracion de encuestas.
		-Registro y eliminación de preguntas.
		-Registro y eliminación de opciones.
	- Listado y filtrado de egresados registrados en el sistema.
	- Generación de estadisticas en base a las encuestas.
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//******************************************** Configuración **********************************************\\
/*
	Configura el idioma de la pagina e importa archivo que contiene la funciones basicas del sistema.
*/
setlocale(LC_ALL,"es_ES");
require_once("PHP/funciones.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//******************************************* Variables básicas ********************************************\\
/*
	Utilizando funciones basicas del sistema obtiene la información necesaria de la base de datos.
*/
$egresados 				 = obtenerUsuariosParse();
$carreras    		     = obtenerObjetos("Carrera", null, null);
$generaciones 			 = obtenerObjetos("Generacion", null, null);
$encuesta_escolares      = obtenerObjetos("Encuesta", array("=" => array("id" => "1")), 1);
$preguntas_escolares     = $encuesta_escolares ? obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_escolares["id"])), null) : 0;
$encuesta_profesionales  = obtenerObjetos("Encuesta", array("=" => array("id" => "2")), 1);
$preguntas_profesionales = $encuesta_profesionales ? obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_profesionales["id"])), null) : 0;
$opciones_encuestas      = obtenerObjetos("Opcion", null, null);
$respuestas 			 = obtenerObjetos("Respuesta", null, null);
$datos_personales 		 = obtenerObjetos("DatosPersonales", null, null);
$datos_escolares 		 = obtenerObjetos("DatosEscolares", null, null);
$datos_profesionales 	 = obtenerObjetos("DatosProfesionales", null, null);
$historial_acceso 	     = obtenerObjetos("HistorialAcceso", null, null);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title> Egresados ITT </title>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet">
		<link href="CSS/MATERIALIZE.css" media="projection,screen" rel="stylesheet" type="text/css"/>
		<link href="CSS/ADMIN.css" media="projection,screen" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-2.0.0.js"> </script>
		<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"> </script>
		<script type="text/javascript" src="//cdn.rawgit.com/MrRio/jsPDF/master/dist/jspdf.min.js"> </script>
		<script type="text/javascript" src="//cdn.rawgit.com/niklasvh/html2canvas/0.5.0-alpha2/dist/html2canvas.min.js"></script>
		<script src="JS/FASTLIVEFILTER.js" type="text/javascript"> </script>
		<script src="JS/ADMIN.js" type="text/javascript"> </script>
		<script src="JS/CHART.js" type="text/javascript"> </script>
		<script src="JS/HTML2CANVAS.js" type="text/javascript"> </script>
		<script src="JS/MATERIALIZE.js" type="text/javascript"> </script>
	</head>
	<body class="grey lighten-3" id="body">
		<header>
				<nav class="header top-nav white">
					<div class="container">
						<div class="nav-wrapper valign-wrapper">
							<ul class="left">
								<li class="white">
									<a> <i class="grey-text material-icons text-darken-3">settings</i> </a>
								</li>
							</ul>
							<a class="grey-text text-darken-2"> PANEL DE ADMINISTRACIÓN </a>
							<ul class="right ml hide-on-large-only">
								<li class="white">
									<a href="PHP/CERRARSESION.php"> <i class="grey-text material-icons text-darken-3">exit_to_app</i> </a>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<nav class="darken-1 teal top-nav valign-wrapper">
					<div class="container">
						<div class="nav-wrapper row nmb">
							<div class="col s12">
								<ul class="darken-1 tabs teal">
									<li class="col tab s4">
										<a class="darken-1 teal white-text" href="#administracion"> Administración </a>
									</li>
									<li class="col tab s4">
										<a class="darken-1 teal white-text" href="#alumnos"> Alumnos </a>
									</li>
									<li class="col tab s4">
										<a class="darken-1 teal white-text" href="#estadisticas"> Estadísticas </a>
									</li>
									<div class="indicator white" style="z-index:1"></div>
								</lu>
							</div>
						</div>
					</div>
				</nav>
		</header>
		<main id="main">
			<div class="white container z-depth-1 " id="container">





				<div class="col pad s12" id="administracion">


					<div class="row">
						<div class="section npad">
							<h4 class="grey-text text-darken-1 thin">Carreras</h4>
						</div>
						<div class="section npad">
							<ul class="collection" id="coleccion_carrera">
								<?php

								//***************************************** Colección de carreras *****************************************\\
								/*
									Despliega una colección de las carreras registradas, con opción para eliminar.
								*/
								if($carreras) {
										foreach($carreras as &$carrera) {
										echo "<li class=\"collection-item\" id=\"" . $carrera["id"] . "\">";
										echo "<span class=\"title\">" . $carrera["nombre"] . "</span>";
										echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Carrera','" . $carrera["id"] . "')\">";
										echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } }

								/////////////////////////////////////////////////////////////////////////////////////////////////////////////
									
								?>
							</ul>
						</div>
						<div class="section npad">
							<form id="formulario_carrera" name="formulario_carrera" onSubmit="return false;">
								<div class="col input-field s8">
									<i class="material-icons prefix">class</i>
									<input id="campo_nombre_carrera" name="campo_nombre_carrera" onkeypress="btnEnterClick(event, 'agregar_carrera','formulario_carrera','precargador_carrera','boton_carrera','coleccion_carrera',$('#campo_nombre_carrera').val())" type="text"/>
									<label for="icon_prefix">Nombre</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_carrera">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_carrera">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_carrera','formulario_carrera','precargador_carrera','boton_carrera','coleccion_carrera',$('#campo_nombre_carrera').val());">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</form>
						</div>
					</div>


					<div class="divider m"></div>


					<div class="row">
						<div class="section npad">
							<h4 class="grey-text text-darken-1 thin">Generaciones</h4>
						</div>
						<div class="section npad">
							<ul class="collection" id="coleccion_generacion">
								<?php

								//*************************************** Colección de generaciones ***************************************\\
								/*
									Despliega una colección de las generaciones registradas, con opción para eliminar.
								*/
								if($generaciones) {
										foreach($generaciones as &$generacion) {
										echo "<li class=\"collection-item\" id=\"" . $generacion["id"] ."\">";
										echo "<span class=\"title\">" . $generacion["periodo"] . "</span>";
										echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Generacion','" . $generacion["id"] . "')\">";
										echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } }

								/////////////////////////////////////////////////////////////////////////////////////////////////////////////

								?>
							</ul>
						</div>
						<div class="section npad">
							<form id="formulario_generacion" onSubmit="return false;">
								<div class="col input-field s8">
									<i class="material-icons prefix">date_range</i>
									<input id="campo_periodo_generacion" name="campo_periodo_generacion" onkeypress="btnEnterClick(event,'agregar_generacion','formulario_generacion','precargador_generacion','boton_generacion','coleccion_generacion',$('#campo_periodo_generacion').val())" type="text"/>
									<label for="icon_prefix">Periodo</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_generacion">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_generacion">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_generacion','formulario_generacion','precargador_generacion','boton_generacion','coleccion_generacion',$('#campo_periodo_generacion').val());">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</form>
						</div>
					</div>


					<div class="divider m"></div>
				

					<div class="row">
						<div class="section npad">
							<h4 class="grey-text text-darken-1 thin">Encuesta escolares</h4>
						</div>
						<?php

						//*********************************** Colección de preguntas escolares ***********************************\\
						/*
							Despliega una colección de las preguntas registradas para la encuesta escolares, 
							con opción para eliminar.
						*/
						if($encuesta_escolares) {
						echo "<div class=\"section npad\">";
						echo "<ul class=\"collection\" id=\"coleccion_preguntas_esc\">";
						if($preguntas_escolares) {
							foreach($preguntas_escolares as &$pregunta){
								echo "<li class=\"collection-item\" id=\"" . $pregunta["id"] . "\">";
								echo "<span class=\"title\">" . $pregunta["pregunta"] . "</span>";
								echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Pregunta','" . $pregunta["id"] . "', 'lista_preguntas_esc')\">";
								echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } }
						echo "</ul></div>"; }

						/////////////////////////////////////////////////////////////////////////////////////////////////////////////

						?>
						<div class="section npad">
							<form id="formulario_preguntas_esc" onSubmit="return false;">
								<div class="col input-field s8">
									<i class="material-icons prefix">help</i>
									<input id="campo_preguntas_esc" name="campo_preguntas_esc" onkeypress="btnEnterClick(event, 'agregar_pregunta_escolares','formulario_preguntas_esc','precargador_preguntas_esc','boton_preguntas_esc','coleccion_preguntas_esc',$('#campo_preguntas_esc').val(),'lista_preguntas_esc')" type="text"/>
									<label for="icon_prefix">Pregunta</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_preguntas_esc">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_preguntas_esc">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_pregunta_escolares','formulario_preguntas_esc','precargador_preguntas_esc','boton_preguntas_esc','coleccion_preguntas_esc',$('#campo_preguntas_esc').val(),'lista_preguntas_esc');">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</form>
						</div>
					</div>


					<div class="row">

						<form id="formulario_opciones_esc" onSubmit="return false;">
							<div class="section npad">
								<div class="col Ddl m input-field s12 m12 l12">
									<select class="DDLPREGUNTAS browser-default" id="lista_preguntas_esc" name="lista_preguntas_esc" onchange="filtrarOpciones('coleccion_opciones_esc','lista_preguntas_esc');">
										<option value="all" selected disabled>Selecciona una pregunta para agregar una opción</option>
										<?php

										//************************************ Lista de preguntas escolares ************************************\\
										/*
											Despliega una lista de las preguntas registradas para la encuesta escolares.
										*/
										if($encuesta_escolares && $preguntas_escolares) {
												foreach($preguntas_escolares as &$pregunta) {
													echo"<option value=\"" . $pregunta["id"] . "\"";
													echo">" . $pregunta["pregunta"] . "</option>"; } }

										/////////////////////////////////////////////////////////////////////////////////////////////////////////////

										?>
									</select>
								</div>
							</div>
							<div class="section npad">
								<ul class="collection" id="coleccion_opciones_esc">
								<?php

								//************************************ Colección de opciones escolares ***********************************\\
								/*
									Despliega una colección de las opciones registradas para la encuesta escolares, 
									con opción para eliminar.
								*/
								if($encuesta_escolares && $preguntas_escolares && $opciones_encuestas) {
									foreach($preguntas_escolares as &$pregunta) {
										$opciones_escolares = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
										foreach($opciones_escolares as &$opcion) {
												echo "<li class=\"collection-item\" data-pregunta=\"" . $pregunta["id"] . "\" id=\"" . $opcion["id"] . "\">";
												echo "<span class=\"title\">" . $opcion["opcion"] . "</span>";
												echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Opcion','" . $opcion["id"] . "')\">";
												echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } } }

								/////////////////////////////////////////////////////////////////////////////////////////////////////////////

								?>
								</ul>
							</div>
							<div class="section npad">
								<div class="col input-field s8">
									<i class="material-icons prefix">more_horiz</i>
									<input id="campo_opciones_esc" name="campo_opciones_esc" onkeypress="btnEnterClick(event, 'agregar_opcion_escolares','formulario_opciones_esc','precargador_opciones_esc','boton_opciones_esc','coleccion_opciones_esc',$('#campo_opciones_esc').val())" type="text"/>
									<label for="icon_prefix">Opcion</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_opciones_esc">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_opciones_esc">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_opcion_escolares','formulario_opciones_esc','precargador_opciones_esc','boton_opciones_esc','coleccion_opciones_esc',$('#campo_opciones_esc').val());">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</div>
						</form>
					</div>


					<div class="divider m"></div>


					<div class="row">
						
						<div class="section npad">
							<h4 class="grey-text text-darken-1 thin">Encuesta profesionales</h4>
						</div>
						<?php

						//********************************* Colección de preguntas profesionales *********************************\\
						/*
							Despliega una colección de las preguntas registradas para la encuesta profesionales, 
							con opción para eliminar.
						*/
						if($encuesta_profesionales) {
							echo "<div class=\"section npad\">";
							echo "<ul class=\"collection\" id=\"coleccion_preguntas_pro\">";
							if($preguntas_profesionales) {
									foreach($preguntas_profesionales as &$pregunta) {
										echo "<li class=\"collection-item\" id=\"" . $pregunta["id"] . "\">";
										echo "<span class=\"title\">" . $pregunta["pregunta"] . "</span>";
										echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Pregunta','" . $pregunta["id"] . "', 'lista_preguntas_pro')\">";
										echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } }
						echo "</ul></div>"; }

						//////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						?>
						<div class="section npad">
							<form id="formulario_preguntas_pro" onSubmit="return false;">
								<div class="col input-field s8">
									<i class="material-icons prefix">help</i>
									<input id="campo_preguntas_pro" name="campo_preguntas_pro" onkeypress="btnEnterClick(event, 'agregar_pregunta_profesionales','formulario_preguntas_pro','precargador_preguntas_pro','boton_preguntas_pro','coleccion_preguntas_pro',$('#campo_preguntas_pro').val(),'lista_preguntas_pro')" type="text"/>
									<label for="icon_prefix">Pregunta</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_preguntas_pro">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_preguntas_pro">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_pregunta_profesionales','formulario_preguntas_pro','precargador_preguntas_pro','boton_preguntas_pro','coleccion_preguntas_pro',$('#campo_preguntas_pro').val(),'lista_preguntas_pro');">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</form>
						</div>
					</div>


					<div class="row">
						<form id="formulario_opciones_pro" onSubmit="return false;">
							<div class="section npad">
								<div class="col Ddl m input-field s12 m12 l12">
									<select class="DDLPREGUNTAS browser-default" id="lista_preguntas_pro" name="lista_preguntas_pro" onchange="filtrarOpciones('coleccion_opciones_pro','lista_preguntas_pro');">";
										<option value="all" selected disabled>Selecciona una pregunta para agregar una opción</option>
										<?php

										//************************************ Lista de preguntas profesionales ***********************************\\
										/*
											Despliega una lista de las preguntas registradas para la encuesta profesionales.
										*/
										if($encuesta_profesionales && $preguntas_profesionales) {
												foreach($preguntas_profesionales as &$pregunta) {
													echo"<option value=\"" . $pregunta["id"] . "\"";
													echo">" . $pregunta["pregunta"] . "</option>"; } }

										/////////////////////////////////////////////////////////////////////////////////////////////////////////////

										?>
									</select>
								</div>
							</div>
							<div class="section npad">
								<ul class="collection" id="coleccion_opciones_pro">
									<?php

									//********************************** Colección de opciones profesionales *********************************\\
									/*
										Despliega una colección de las opciones registradas para la encuesta profesionales, 
										con opción para eliminar.
									*/
									if($encuesta_profesionales && $preguntas_profesionales && $opciones_encuestas) {
										foreach($preguntas_profesionales as &$pregunta) {
											$opciones_profesionales = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
											foreach($opciones_profesionales as &$opcion) {
													echo "<li class=\"collection-item\" data-pregunta=\"" . $pregunta["id"] . "\" id=\"" . $opcion["id"] . "\">";
													echo "<span class=\"title\">" . $opcion["opcion"] . "</span>";
													echo "<a class=\"secondary-content\" onclick=\"eliminarObjeto('Opcion','" . $opcion["id"] . "')\">";
													echo "<i class=\"material-icons red-text text-accent-1\">remove_circle</i></a></li>"; } } }

									/////////////////////////////////////////////////////////////////////////////////////////////////////////////

									?>
								</ul>
							</div>
							<div class="section npad">
								<div class="col input-field s8">
									<i class="material-icons prefix">more_horiz</i>
									<input id="campo_opciones_pro" name="campo_opciones_pro" onkeypress="btnEnterClick(event, 'agregar_opcion_profesionales','formulario_opciones_pro','precargador_opciones_pro','boton_opciones_pro','coleccion_opciones_pro',$('#campo_opciones_pro').val())" type="text"/>
									<label for="icon_prefix">Opcion</label>
								</div>
								<div class="active right preloader-wrapper small" id="precargador_opciones_pro">
			    					<div class="spinner-blue-only spinner-layer">
			    						<div class="circle-clipper left"><div class="circle"></div></div>
					                  	<div class="gap-patch"><div class="circle"></div></div>
					                  	<div class="circle-clipper right"><div class="circle"></div></div>
					                </div>
					            </div>
								<div class="col input-field s4" id="boton_opciones_pro">
									<a class="blue right thin btn lighten-1 btn-flat" onclick="enviarFormulario('agregar_opcion_profesionales','formulario_opciones_pro','precargador_opciones_pro','boton_opciones_pro','coleccion_opciones_pro',$('#campo_opciones_pro').val());">
										<i class="material-icons white-text">add</i>
									</a>
								</div>
							</div>
						</form>
					</div>


					<div class="divider m"></div>


				</div>





				<div class="col pad s12" id="alumnos">
					<nav>
				  		<div class="nav-wrapper teal darken-1">
				  			<form class="ui-filterable">
					        	<div class="input-field">
					          		<input id="search" type="search" data-type="search" onkeydown="setDDL();" required>
					          		<label for="search"><i class="material-icons">search</i></label>
					          		<i class="material-icons">close</i>
					        	</div>
					      	</form>
				    	</div>
				  	</nav>
				  	<form id="FMALUMNOS">
					  	<div class="m row" style="margin:15px 0px 12px 0px;">
					  		 <div class="input-field Ddl col s12 l6 m">
					  			<select class="browser-default" id="lista_carreras" name="lista_carreras" onchange="filtrarAlumnos();">
									<option value="all" selected>Todas las carreras</option>
									<?php

									//***************************************** Lista de carreras *****************************************\\
									/*
										Despliega una lista de las carreras registradas.
									*/
									if($carreras) {
											foreach($carreras as &$carrera) {
												echo"<option value=\"" . $carrera["nombre"] . "\">" . $carrera["nombre"] . "</option>"; } }

									/////////////////////////////////////////////////////////////////////////////////////////////////////////////

									?>
	    						</select>
					  		</div>
					  		<div class="input-field Ddl col s12 l6 m">
					  			<select class="browser-default" id="lista_generacion" name="lista_generacion" onchange="filtrarAlumnos();">
									<option value="all" selected>Todas las generaciones</option>
										<?php
										
										//***************************************** Lista de generaciones *****************************************\\
										/*
											Despliega una lista de las generaciones registradas.
										*/
											if($generaciones) {
												foreach($generaciones as &$generacion) {
												echo "<option value=\"" . $generacion["periodo"] . "\">" . $generacion["periodo"] . "</option>"; } }
										
										//////////////////////////////////////////////////////////////////////////////////////////////////////////////
												
									?>
		    					</select>
					  		</div>
		        		</div>
	        		</form>
					<ul id="coleccion_egresados" class="collection" data-filter="true" data-input="#search">
						<?php
						
						//***************************************** colección de egresados ****************************************\\
						/*
							- Despliega una colección de los egresados registrados en el sistema.
							- Obtiene los datos(personales, escolares, profesionales) para cada egresado.
							- Muestra nombre, telefono y correo, además agrega la carrera y generacion
							en forma de informacion personalizada(data-) para realizar filtros dinamicamente. 
							- Muestra un indicador para identificar/sugerir actualización de datos del egresado correspondiente
							(no completado o datos obsoletos/historial de acceso).
							- Agrega boton para envio de correo electronico para cada egresado.
						*/
						if($datos_personales && $datos_escolares && $datos_profesionales) {
							foreach($egresados as &$egresado) {
								$datos_personales_egresado    = filtrarObjetos($datos_personales, array("==" => array("id_egresado" => $egresado -> getObjectId())), 1);
								$datos_escolares_egresado     = filtrarObjetos($datos_escolares, array("==" => array("id_egresado" => $egresado -> getObjectId())), 1);
								$datos_profesionales_egresado = filtrarObjetos($datos_profesionales, array("==" => array("id_egresado" => $egresado -> getObjectId())), 1);
								if($datos_personales_egresado && $datos_escolares_egresado && $datos_profesionales_egresado) {
									$carrera    = $datos_escolares_egresado["id_carrera"] ? obtenerObjetos("Carrera", array("=" => array("id" => $datos_escolares_egresado["id_carrera"])), 1) : 0 ;
									$generacion = $datos_escolares_egresado["id_generacion"] ? obtenerObjetos("Generacion", array("=" => array("id" => $datos_escolares_egresado["id_generacion"])), 1) : "" ;
									echo "<li class=\"collection-item\" data-carrera=\""; if($carrera) { echo $carrera["nombre"]; } echo "\" data-generacion=\""; if($generacion) { echo $generacion["periodo"]; } echo "\">";
									echo "<span class=\"title\">" . $datos_personales_egresado["nombres"] . " " . $datos_personales_egresado["apellidos"] . "</span>";
									echo "<br><span class=\"title\">Correo: " . $egresado -> get("email") . "</span>";
									echo "<br><span class=\"title\">Telefono: " . $datos_personales_egresado["telefono"] . "</span>";
									$historial_acceso_egresado = filtrarObjetos($historial_acceso, array("==" => array("id_egresado" => $egresado -> getObjectId())), 1);
									$dias = $historial_acceso_egresado ? date_diff(new DateTime($historial_acceso_egresado["fecha"]), new DateTime(date("Y/m/d"))) -> days : 0 ;
									if($dias < 30 && $datos_personales_egresado["completado"] && $datos_escolares_egresado["completado"] && $datos_profesionales_egresado["completado"]) {
										echo "<a class=\"secondary-content\"><i class=\"material-icons teal-text text-accent-4\">check_circle</i></a>"; }
									else{ echo "<a class=\"secondary-content\"><i class=\"material-icons red-text text-accent-1\">error</i></a>"; }
									echo "<a href=\"javascript:void(0)\" onclick=\"enviarMail('" . $egresado -> get("email") . "')\" class=\"secondary-content\"><i class=\"material-icons blue-text text-lighten-2\">email</i></a>";
									echo "</li>"; } } }

						//////////////////////////////////////////////////////////////////////////////////////////////////////////////

						?>
					</ul>
				</div>





				<div class="col pad s12" id="estadisticas">
					<?php
					//***************************************** Estadisticas escolares ****************************************\\
					/*
						- Obtiene las opciones disponibles para cada pregunta de la encuesta escolares (filtra todas las opciones).
						- Genera graficas dinamicamente con los datos arrojados por la encuesta (Utilizando Chart.js).
					*/
						
						if($encuesta_escolares && $preguntas_escolares && $respuestas) {
							foreach($preguntas_escolares as &$pregunta) {
								echo"<div class=\"section\"><h5 class=\"grey-text text-darken-1 thin\">" . $pregunta["pregunta"] . "</h5>";
									echo"<div class=\"row\">";
										echo"<div class=\"col s12 m4 l3\">";
											echo"<ul class=\"collection with-header\">";
									$opciones_escolares = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
									foreach($opciones_escolares as &$opcion) {
										$respuestas_escolares_egresado = filtrarObjetos($respuestas, array("==" => array("id_opcion" => $opcion["id"])));
											echo"<li class=\"grey-text text-darken-1 light collection-item\"><div>" . $opcion["opcion"] . ": " . count($respuestas_escolares_egresado) . "</div></li>"; }
											echo "</ul>";
										echo"</div>";
										echo"<div class=\"center col s12 m8 l9\">";
											echo"<canvas id=\"canvas" . $pregunta["id"] . "\"></canvas>";
											echo"<script type=\"text/javascript\">";
												echo"$(document).ready(function(){";
													echo"var Data" . $pregunta["id"] . "={";
														echo"labels:[";
															foreach($opciones_escolares as &$opcion) { echo "\"" . $opcion["opcion"] . "\","; }
														echo"],";
														echo"datasets:[{";
										                    echo"fillColor:\"rgba(100,181,246,1.0)\",";
										                    echo"strokeColor:\"rgba(220,220,220,0.8)\",";
										                    echo"highlightFill:\"rgba(220,220,220,0.75)\",";
										                    echo"highlightStroke:\"rgba(220,220,220,1.0)\",";
										                    echo"data:[";
										                    foreach($opciones_escolares as &$opcion) {
										                    	$respuestas_escolares_egresado = filtrarObjetos($respuestas, array("==" => array("id_opcion" => $opcion["id"])));
										                    	echo count($respuestas_escolares_egresado) . ","; }
															echo"]";
													echo"}]};";
													echo"var grafica" . $pregunta["id"] . "=$(\"#canvas" . $pregunta["id"] . "\").get(0).getContext(\"2d\");";
													echo"var image = new Image();";
													echo"var chart" . $pregunta["id"] . "=new Chart(grafica" . $pregunta["id"] . ").Bar(Data" . $pregunta["id"] . ",{onAnimationComplete:function(){image.src = $(\"#canvas" . $pregunta["id"] . "\").get(0).toDataURL(); $(\"#canvas" . $pregunta["id"] . "\").replaceWith(image); },responsive:false});";
												echo"});";
											echo"</script>";
										echo"</div>";
									echo"</div>";
								echo"</div>"; } }

					//////////////////////////////////////////////////////////////////////////////////////////////////////////////
						


					//**************************************** Estadisticas profesionales ***************************************\\	
					/*
						- Obtiene las opciones disponibles para cada pregunta de la encuesta profesionales (filtra todas las opciones).
						- Genera graficas dinamicamente con los datos arrojados por la encuesta (Utilizando Chart.js).
					*/
						if($encuesta_profesionales && $preguntas_profesionales && $respuestas) {
							foreach($preguntas_profesionales as &$pregunta) {
								echo"<div class=\"section\"><h5 class=\"grey-text text-darken-1 thin\">" . $pregunta["pregunta"] . "</h5>";
									echo"<div class=\"row\">";
										echo"<div class=\"col s12 m4 l3\">";
											echo"<ul class=\"collection with-header\">";
									$opciones_profesionales = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
									foreach($opciones_profesionales as &$opcion) {
										$respuestas_profesionales_egresado = filtrarObjetos($respuestas, array("==" => array("id_opcion" => $opcion["id"])));
											echo"<li class=\"grey-text text-darken-1 light collection-item\"><div>" . $opcion["opcion"] . ": " . count($respuestas_profesionales_egresado) . "</div></li>"; }
											echo "</ul>"; 
										echo"</div>";
										echo"<div class=\"center col s12 m8 l9\">";
											echo"<canvas id=\"canvas" . $pregunta["id"] . "\"></canvas>";
											echo"<script type=\"text/javascript\">";
												echo"$(document).ready(function(){";
													echo"var Data" . $pregunta["id"] . "={";
														echo"labels:[";
															foreach($opciones_profesionales as &$opcion) { echo "\"" . $opcion["opcion"] . "\","; }
														echo"],";
														echo"datasets:[{";
										                    echo"fillColor:\"rgba(100,181,246,1.0)\",";
										                    echo"strokeColor:\"rgba(220,220,220,0.8)\",";
										                    echo"highlightFill:\"rgba(220,220,220,0.75)\",";
										                    echo"highlightStroke:\"rgba(220,220,220,1.0)\",";
										                    echo"data:[";
										                    foreach($opciones_profesionales as &$opcion) {
										                    	$respuestas_profesionales_egresado = filtrarObjetos($respuestas, array("==" => array("id_opcion" => $opcion["id"])));
										                    	echo count($respuestas_profesionales_egresado) . ","; }
															echo"]";
													echo"}]};";
													echo"var grafica" . $pregunta["id"] . "=$(\"#canvas" . $pregunta["id"] . "\").get(0).getContext(\"2d\");";
													echo"var image = new Image();";
													echo"var chart" . $pregunta["id"] . "=new Chart(grafica" . $pregunta["id"] . ").Bar(Data" . $pregunta["id"] . ",{onAnimationComplete:function(){image.src = $(\"#canvas" . $pregunta["id"] . "\").get(0).toDataURL(); $(\"#canvas" . $pregunta["id"] . "\").replaceWith(image); },responsive:false});";
												echo"});";
											echo"</script>";
										echo"</div>";
									echo"</div>";
								echo"</div>"; } }
								
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
					?>
				</div>

	 			<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
				 	<a class="btn-floating btn-large red">
				 		<i class="large material-icons">mode_edit</i>
			    	</a>
				    <ul>
				      	<li><a class="btn-floating green" onclick="printToImage();"><i class="material-icons">publish</i></a></li>
			    	</ul>
			  	</div>


			</div>
		</main>
		<footer>
			<div class="darken-1 footer-copyright teal valign-wrapper">
				<div class="container">
					<p class="right thin white-text">
						<a class="white-text" href="http://tectijuana.edu.mx/">Instituto Nacional de México</a>
						- Derechos reservados ©2016</p>
				</div>
			</div>
		</footer>
	</body>
</html>