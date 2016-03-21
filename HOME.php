<?php

//******************************************* panel de egresado *******************************************\\
/*
	Este archivo contiene las funciones para la recavación de datos que permiten:
	- modificación de datos personales, escolares y profesionales.
	- Llenado y modificación de encuestas.
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
	Redirige a la pagina principal  si no existe una sesión activa.
*/
$usuario_actual			 = obtenerUsuarioActual();
if(!$usuario_actual) { header("Location:INDEX.php"); }
$carreras 				 = obtenerObjetos("Carrera", null, null);
$generaciones 		     = obtenerObjetos("Generacion", null, null);
$opciones_encuestas      = obtenerObjetos("Opcion", null, null);
$encuesta_escolares      = obtenerObjetos("Encuesta", array("=" => array("id" => "1")), 1);
$preguntas_escolares     = $encuesta_escolares ? obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_escolares["id"])), null) : 0;
$encuesta_profesionales  = obtenerObjetos("Encuesta", array("=" => array("id" => "2")), 1);
$preguntas_profesionales = $encuesta_profesionales ? obtenerObjetos("Pregunta", array("=" => array("id_encuesta" => $encuesta_profesionales["id"])), null) : 0;
$datos_personales        = obtenerObjetos("DatosPersonales", array("=" => array("id_egresado" => "\"" . $usuario_actual -> getObjectId() . "\"")), 1);
$datos_escolares         = obtenerObjetos("DatosEscolares", array("=" => array("id_egresado" => "\"" . $usuario_actual -> getObjectId() . "\"")), 1);
$datos_profesionales     = obtenerObjetos("DatosProfesionales", array("=" => array("id_egresado" => "\"" . $usuario_actual -> getObjectId() . "\"")), 1);
$respuestas        		 = obtenerObjetos("Respuesta", null, null);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta charset="utf-8">
      	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Egresados ITT</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      	<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" rel="stylesheet">
        <link href="CSS/MATERIALIZE.css" media="projection,screen" rel="stylesheet" type="text/css"/>
        <link href="CSS/HOME.css" media="projection,screen" rel="stylesheet" type="text/css"/>
        <script src="http://code.jquery.com/jquery-2.0.0.js"></script>
        <script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
        <script src="JS/HOME.js" type="text/javascript"></script>
        <script src="JS/MATERIALIZE.js" type="text/javascript"></script>
	</head>
	<body class="grey lighten-3">
		<header>
		    <nav class="header top-nav white">
		    	<div class="container">
			        <div class="nav-wrapper valign-wrapper">
		          		<ul class="left">
			            	<li class="white"><a><i class="grey-text material-icons text-darken-3">school</i></a></li>
			          	</ul>
			          	<a class="grey-text text-darken-2">DATOS DEL EGRESADO</a>
			          	<ul class="right ml">
			          		<li class="white"><a href="PHP/cerrar_sesion.php"><i class="grey-text material-icons text-darken-3">exit_to_app</i></a></li>
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
			                		<a class="darken-1 teal white-text" href="#personales">Personales</a>
			                	</li>
				                <li class="col tab s4">
				                	<a class="darken-1 teal white-text" href="#escolares">Escolares</a>
				                </li>
				                <li class="col tab s4">
				                	<a class="darken-1 teal white-text" href="#profesionales">Profesionales</a>
				                </li>
				                <div class="indicator white" style="z-index:1"></div>
							</lu>
						</div>
	        		</div>
	      		</div>
	    	</nav>
		</header>
		<main>
	    	<div class="white container z-depth-1">





				<div class="col pad s12" id="personales">
	    			<form class="col s12" id="formulario_personales" name="formulario_personales" onSubmit="return false;">


	    				<div class="nmb row">
		    				<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">account_box</i>
								<input id="campo_nombres" name="campo_nombres" type="text" value="<?php if($datos_personales) { echo $datos_personales["nombres"]; } ?>"/>
								<label for="icon_prefix">Nombres</label>
							</div>
							<div class="col input-field s12 m6 l6">
								<input id="campo_apellidos" name="campo_apellidos" type="text" value="<?php if($datos_personales) { echo $datos_personales["apellidos"]; } ?>"/>
								<label for="icon_prefix">Apellidos</label>
							</div>
		    			</div>


		    			<div class="divider mb hide-on-small-only"></div>
		    			

		    			<div class="nmb row">
		    				<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">cake</i>
								<input class="datepicker" id="campo_fecha_nacimiento" name="campo_fecha_nacimiento" type="text" data-value="<?php if($datos_personales && $datos_personales["fecha_nacimiento"] != "") { echo $datos_personales["fecha_nacimiento"]; } ?>"/>
								<label for="icon_prefix">Fecha nacimiento</label>
							</div>
							<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">subtitles</i>
								<input id="campo_curp" name="campo_curp" style="text-transform:uppercase;" type="text" value="<?php if($datos_personales) { echo $datos_personales["curp"]; } ?>"/>
								<label for="icon_prefix">Curp</label>
							</div>
		    			</div>


		    			<div class="divider mb hide-on-small-only"></div>
		    			

		    			<div class="nmb row">
		    				<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">contact_mail</i>
								<input type="text" id="campo_correo" name="campo_correo" value="<?php echo $usuario_actual -> get("email") ?>"/>
								<label for="icon_prefix">Correo electrónico</label>
							</div>
							<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">contact_phone</i>
								<input id="campo_telefono" name="campo_telefono" type="text" onkeypress="btnEnterClick(event)" value="<?php if($datos_personales) { echo $datos_personales["telefono"]; } ?>"/>
								<label for="icon_prefix">Teléfono</label>
							</div>
		    			</div>


						<br><div class="divider"></div><br>
		    			

		    			<div class="row right">
		    				<div class="active preloader-wrapper small" id="precargador_personales">
		    					<div class="spinner-blue-only spinner-layer">
		    						<div class="circle-clipper left"><div class="circle"></div></div>
				                  	<div class="gap-patch"><div class="circle"></div></div>
				                  	<div class="circle-clipper right"><div class="circle"></div></div>
				                </div>
				            </div>
		    				<div class="col input-field s12" id="boton_personales">
		    					<a class="blue btn lighten-1" onclick="DatosPersonales();">
		                        	<i class="material-icons">check</i>
		                      	</a>
							</div>
		    			</div>


	    			</form>
	    		</div>





				<div class="col pad s12" id="escolares">
	    			<form class="col s12" id="formulario_escolaraes">


	    				<div class="row">
		    				<div class="col input-field s12 m6 l6">
		    					<i class="material-icons prefix">featured_play_list</i>
								<input type="text" value="<?php if($datos_escolares) { echo $datos_escolares["numero_control"]; } ?>" readonly/>
								<label for="icon_prefix">Número de control</label>
							</div>
							<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">class</i>
								<select id="lista_carrera" name="lista_carrera">
									<option value="" disabled  <?php if($datos_escolares && !$datos_escolares["id_carrera"]) { echo"selected"; } ?>>Seleccione su carrera</option>
									<?php

									//***************************************** Lista de carreras *****************************************\\
									/*
										Despliega una lista de las carreras registradas.
									*/

									if($carreras && $datos_escolares) {
										foreach($carreras as &$carrera) {
											echo"<option value=\"" . $carrera["id"] . "\"";
											if($datos_escolares["id_carrera"] == $carrera["id"]) { echo"selected"; }
											echo">" . $carrera["nombre"] . "</option>"; } }

									//////////////////////////////////////////////////////////////////////////////////////////////////////////////

									?>
	        					</select>
	        					<label>Carrera</label>
							</div>
		    			</div>


		    			<div class="M row">
		    				<div class="col input-field s12 m6 l6">
		    					<i class="material-icons prefix">date_range</i>
	    						<select id="lista_generacion" name="lista_generacion">
									<option value="" disabled  <?php if($datos_escolares && !$datos_escolares["id_generacion"]) { echo"selected"; } ?>>Seleccione su generación</option>
									<?php

									//***************************************** Lista de generaciones *****************************************\\
									/*
										Despliega una lista de las generaciones registradas.
									*/
									if($generaciones && $datos_escolares) { 
										foreach($generaciones as &$generacion) {
										echo "<option value=\"" . $generacion["id"] . "\"";
										if($datos_escolares["id_generacion"] == $generacion["id"]) { echo"selected"; }
										echo ">" . $generacion["periodo"] . "</option>"; } }

									//////////////////////////////////////////////////////////////////////////////////////////////////////////////

									?>
		    					</select>
		    					<label>Generación</label>
		    				</div>
		    			</div>


		    			<div class="divider"></div>


		    			<div class="row">
							<?php
							//***************************************** Encuesta escolares *****************************************\\
							/*
								Despliega una la encuesta escolares(preguntas y opciones).
							*/

							if($encuesta_escolares && $preguntas_escolares) {
									foreach($preguntas_escolares as &$pregunta){
										echo "<div class=\"input-field col s12\"><label>" . $pregunta["pregunta"] . "</label><br/></div>";
										$opciones_escolares = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
										foreach($opciones_escolares as &$opcion){
											$respuestas_escolares_egresado = $respuestas ? filtrarObjetos($respuestas, array("==" => array("id_egresado" => $usuario_actual -> getObjectId(), "id_pregunta" => $pregunta["id"])), 1) : 0;
											echo "<div class=\"input-field col m6 l3 s12\">";
											echo "<input name=\"" . $pregunta["id"] . "\" type=\"radio\" id=\"" . $opcion["id"] . "\" value=\"" . $opcion["id"] ."\"";
											if($respuestas_escolares_egresado && $respuestas_escolares_egresado["id_opcion"] == $opcion["id"]) { echo "checked"; }
											echo "/><label for=\"" . $opcion["id"] . "\">" . $opcion["opcion"] . "</label></div>"; } } }

							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
							
							?>
						</div>


						<br><div class="divider"></div><br>


		    			<div class="row right">
		    				<div class="active preloader-wrapper small" id="precargador_escolares">
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
		    				<div class="col input-field s12" id="boton_escolares">
		    					<a class="blue btn lighten-1" onclick="DatosEscolares();">
		                        	<i class="material-icons">check</i>
		                      	</a>
							</div>
		    			</div>


	    			</form>
		    	</div>





		    	<div id="profesionales" class="col pad s12">
			    	<form class="col s12" id="formulario_profesionales">


		    			<div class="row">
		    				<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">translate</i>
								<select id="lista_idioma" name="lista_idioma">
									<option value="" disabled <?php if($datos_profesionales && $datos_profesionales["segundo_idioma"] == "") { echo"selected"; } ?>>Selecciona una opción</option>
									<option value="Ingles" <?php if($datos_profesionales && $datos_profesionales["segundo_idioma"] == "Ingles") { echo"selected"; } ?>>Inglés</option>
									<option value="Aleman" <?php if($datos_profesionales && $datos_profesionales["segundo_idioma"] == "Aleman") { echo"selected"; } ?>>Alemán</option>
									<option value="Mandarin" <?php if($datos_profesionales && $datos_profesionales["segundo_idioma"] == "Mandarin") { echo"selected"; } ?>>Mandarin</option>
									<option value="Ninguno" <?php if($datos_profesionales && $datos_profesionales["segundo_idioma"] == "Ninguno") { echo"selected"; } ?>>Ninguno</option>
	        					</select>
	        					<label>Segundo idioma</label>
							</div>
							<div class="col input-field s12 m6 l6">
								<i class="material-icons prefix">work</i>
								<input id="campo_empresa" name="campo_empresa" type="text" value="<?php if($datos_profesionales) { echo $datos_profesionales["empresa"]; } ?>"/>
								<label for="icon_prefix">Empresa (opcional)</label>
							</div>
		    			</div>




		    			<div class="divider"></div>


		    			<div class="row">
							<?php
							//*************************************** Encuesta profesionales ***************************************\\
							/*
								Despliega una la encuesta profesionales(preguntas y opciones).
							*/

								if($encuesta_profesionales && $preguntas_profesionales) {
										foreach($preguntas_profesionales as &$pregunta){
											echo "<div class=\"input-field col s12\"><label>" . $pregunta["pregunta"] . "</label><br/></div>";
											$opciones_profesionales = filtrarObjetos($opciones_encuestas, array("==" => array("id_pregunta" => $pregunta["id"])));
											foreach($opciones_profesionales as &$opcion){
												$respuestas_profesionales_egresado = $respuestas ?  filtrarObjetos($respuestas, array("==" => array("id_egresado" => $usuario_actual -> getObjectId() , "id_pregunta" => $pregunta["id"])), 1) : 0;
												echo "<div class=\"input-field col m6 l3 s12\">";
												echo "<input name=\"" . $pregunta["id"] . "\" type=\"radio\" id=\"" . $opcion["id"] . "\" value=\"" . $opcion["id"] ."\"";
												if($respuestas_profesionales_egresado && $respuestas_profesionales_egresado["id_opcion"] == $opcion["id"]) { echo "checked"; }
												echo "/><label for=\"" . $opcion["id"] . "\">" . $opcion["opcion"] . "</label></div>"; } } }
												
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////
							?>
						</div>


		    			<br><div class="divider"></div><br>
		    			

		    			<div class="row right">
		    				<div class="active preloader-wrapper small" id="precargador_profesionales">
		    					<div class="spinner-blue-only spinner-layer">
		    						<div class="circle-clipper left"><div class="circle"></div></div>
				                  	<div class="gap-patch"><div class="circle"></div></div>
				                  	<div class="circle-clipper right"><div class="circle"></div></div>
				                </div>
				            </div>
		    				<div class="col input-field s12" id="boton_profesionales">
		    					<a class="blue btn lighten-1" onclick="DatosProfesionales();">
		                        	<i class="material-icons">check</i>
		                      	</a>
							</div>
		    			</div>


			    	</form>
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