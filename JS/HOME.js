//******************************************* Panel de usuario *******************************************\\
/*
    Este archivo contiene las funciones para la actualización de los datos del egresado
    asi como de las encuestas.
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//***************************************** Configuración inicial *****************************************\\
/*
    Prepara la animación de carga en el envio de formularios.
    Configura el calendario para mostrar fechas con un formatlo mas legible.
*/
$(document).ready(function() { 
    $("select").material_select();
    $("#precargador_personales").hide();
    $("#precargador_escolares").hide();
    $("#precargador_profesionales").hide();
    $(".datepicker").pickadate( {
        clear: "Borrar",
        close: "Cerrar",
        date_max: false,
        formatSubmit: "yyyy/mm/dd",
        hiddenName: true,
        date_min: false,
        max: true,
        monthsFull: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
        monthsShort: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        selectMonths: true,
        selectYears: 50,
        today: "Hoy",
        weekdaysFull: ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"],
        weekdaysShort: ["D","L","M","M","J","V","S"] } ); } );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Detección de clicks *** ****************************************\\
/*
    Detecta click en el boton enter(Para enviar formulario al servidor).
*/
function btnEnterClick(e) { if(e.keyCode == 13) { DatosPersonales(); return false; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************* Envio de formulario personales **************************************\\
/*
    Envia un formulario HTML al servidor mediante AJAX, verificando que ninguno de los campos se
    encuentre vacio.
*/
function DatosPersonales() {
    if($("#campo_nombres").val() != "" && $("#campo_apellidos").val() != "" && $("#campo_fecha_nacimiento").val() != "" && $("#campo_curp").val() != "" && $("#campo_correo").val() != "" && $("#campo_telefono").val() != "") {
        var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if($("#campo_correo").val().match(pattern)) {
            var pattern = /^[0-9]+$/;
            if($("#campo_telefono").val().match(pattern)) {
                var data = $("#formulario_personales").serialize();
                $.ajax( {
                    data: data,
                    type: "POST",
                    url: "PHP/datos_personales.php",
                    beforeSend: function() {
                        $("#boton_personales").hide();
                        $("#precargador_personales").show(); },
                    success: function(response) {
                        $("#boton_personales").show();
                        $("#precargador_personales").hide();
                        var data = $.parseJSON(response);
                        if(data["codigo_error"] == "0") {
                            Materialize.toast(data["descripcion"]["mensaje"],1000);
                            $("ul.tabs").tabs("select_tab","escolares"); }
                        else { Materialize.toast(data["descripcion"]["mensaje"],1000); } },
                    error: function() {
                        $("#boton_personales").show();
                        $("#precargador_personales").hide();
                        Materialize.toast("Ocurrio un error",1000); } } ); }
            else { Materialize.toast("Telefono solo puede contener numeros",1000); } }
        else { Materialize.toast("Correo invalido",1000); } }
    else { Materialize.toast("Campos incompletos",1000); }
    return false; }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************* Envio de formulario escolares **************************************\\
/*
    Envia un formulario HTML al servidor mediante AJAX, verificando que ninguno de los campos se
    encuentre vacio.
*/
function DatosEscolares() {
    if($("#lista_carrera").val() && $("#lista_generacion").val()) {
        var data = $("#formulario_escolaraes").serialize();
        $.ajax( {
            data: data,
            type: "POST",
            url: "PHP/datos_escolares.php",
            beforeSend: function() {
                $("#boton_escolares").hide();
                $("#precargador_escolares").show(); },
            success: function(response) {
                $("#boton_escolares").show();
                $("#precargador_escolares").hide();
                var data = $.parseJSON(response);
                if(data["codigo_error"] == "0") {
                    Materialize.toast(data["descripcion"]["mensaje"], 1000);
                    $("ul.tabs").tabs("select_tab","profesionales"); }
                else { Materialize.toast(data["descripcion"]["mensaje"], 2000); } },
            error: function() {
                $("#boton_escolares").show();
                $("#precargador_escolares").hide();
                Materialize.toast("Ocurrio un error", 1000); } } ); }
    else { Materialize.toast("Campos incompletos", 1000);
    return false; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************ Envio de formulario profesionales ************************************\\
/*
    Envia un formulario HTML al servidor mediante AJAX, verificando que ninguno de los campos se
    encuentre vacio.
*/
function DatosProfesionales() {
    if($("#lista_idioma").val() != null) {
        var data = $("#formulario_profesionales").serialize();
        $.ajax( {
            data: data,
            type: "POST",
            url: "PHP/datos_profesionales.php",
            beforeSend: function() {
                $("#boton_profesionales").hide();
                $("#precargador_profesionales").show(); },
            success: function(response) {
                $("#boton_profesionales").show();
                $("#precargador_profesionales").hide();
                var data = $.parseJSON(response);
                if(data["codigo_error"] == "0") {
                    Materialize.toast(data["descripcion"]["mensaje"], 1000);
                    $("ul.tabs").tabs("select_tab","personales"); }
                else { Materialize.toast(data["descripcion"]["mensaje"], 2000); } },
                    error: function() {
                        $("#boton_profesionales").show();
                        $("#precargador_profesionales").hide();
                        Materialize.toast("Ocurrio un error", 1000); } } ); }
    else{ Materialize.toast("Campos incompletos", 1000);
    return false; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////