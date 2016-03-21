//******************************************* Inicio de sesión *******************************************\\
/*
    Este archivo contiene las funciones para realizar el inicio de sesión.
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//***************************************** Configuración inicial *****************************************\\
/*
    Prepara la animación de carga en el envio de formularios.
*/
$(document).ready(function() {
    $("#precargador_entrar").hide();
    $("#precargador_registro").hide();
    $("#precargador_recuperar").hide(); });

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Detección de clicks *** ****************************************\\
/*
    Detecta click en el boton enter(Para enviar formulario al servidor).
*/
function btnEnterClick(e, url, formulario, precargador, boton) {
    if(e.keyCode == 13) {
        enviarFormulario(url, formulario, precargador, boton);
        return false; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Envio de formulario *******************************************\\
/*
    Envia un formulario HTML al servidor mediante AJAX.
    Recibe como parametro, url del servidor, formulario HTML, boton y precargador para realizar animación.
*/
function enviarFormulario(url, formulario, precargador, boton) {
    if(verificarFormulario(document.getElementById(formulario))) {
        var data = $("#" + formulario).serialize();
        $.ajax( {
            data: data,
            type: "POST",
            url: "PHP/" + url + ".php",
            beforeSend: function() {
                $("#" + boton).hide();
                $("#" + precargador).show(); },
            success: function(response) {
                $("#" + boton).show();
                $("#" + precargador).hide();
                var data = $.parseJSON(response);
                if(data["codigo_error"] == "0") {
                    $("#" + formulario)[0].reset(); 
                    if(data["descripcion"]["destino"]) { window.location.assign(data["descripcion"]["destino"]); }
                    Materialize.toast(data["descripcion"]["mensaje"],1000); }
                else{ Materialize.toast(data["descripcion"]["mensaje"],1000); } },
            error: function(){
                $("#" + boton).hide();
                $("#" + precargador).show();
                Materialize.toast("Ocurrio un error",1000); } }); } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//*************************************** verificación de formulario ****************************************\\
/*
    Verifica que no existan campos vacios en el formulario a enviar.
*/
function verificarFormulario(formulario) {
    const elementos = formulario.elements; 
    var verificado = true;
    var campo = "";
    for (contador = 0; contador < formulario.length; contador++) {
        if(elementos[contador].value == "") {
            verificado = false;
            Materialize.toast(elementos[contador].id.replace(/_/g, " ") + " incompleto", 1000);
            break; } }
    return verificado; }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////