//*************************************** Administrador del sistema ***************************************\\
/*
    Este archivo contiene las funciones para la administración del sistema que permiten:
    - Envio de formularios al servidor mediante AJAX.
    - Captura de la pagina.
    - Filtrado dinamico.
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////



//***************************************** Configuración inicial *****************************************\\
/*
    Inicializa los filtros dinámicos.
    Prepara la animación de carga en el envio de formularios.
*/
$(document).ready(function() {
    $("#precargador_carrera").hide();
    $("#precargador_generacion").hide();
    $("#precargador_preguntas_esc").hide();
    $("#precargador_preguntas_pro").hide();
    $("#precargador_opciones_esc").hide();
    $("#precargador_opciones_pro").hide();
    $(".tooltipped").tooltip({delay:50});
    $("#search").fastLiveFilter("#coleccion_egresados");
    filtrarOpciones("coleccion_opciones_esc","lista_preguntas_esc");
    filtrarOpciones("coleccion_opciones_pro","lista_preguntas_pro"); });

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Detección de clicks *** ****************************************\\
/*
    Detecta click en el boton enter(Para enviar formulario al servidor).
*/
function btnEnterClick(e, url, formulario, precargador, boton, coleccion, valor, lista) {
    if(e.keyCode == 13) {
        enviarFormulario(url, formulario, precargador, boton, coleccion, valor, lista);
        return false; } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//****************************************** Envio de formulario *******************************************\\
/*
    Envia un formulario HTML al servidor mediante AJAX.
    Recibe como parametro, url del servidor, formulario HTML, boton y precargador para realizar animación,
    colección, lista y valor son parametros opcionales que se usaran en caso de que sea necesario agregar
    dinamicamente en pantalla una nueva opción.
*/
function enviarFormulario(url, formulario, precargador, boton, coleccion, valor, lista) {
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
                    Materialize.toast(data["descripcion"]["mensaje"],1000);
                    if(coleccion && valor) {
                        var lista_borrar = "";
                        if(lista) { lista_borrar = ",\'" + lista + "\'" ; }
                        $("#" + coleccion).append(
                        "<li class=\"collection-item\" id=\"" + data["descripcion"]["id"] + "\" data-pregunta=\"" + data["descripcion"]["id_pregunta"] + "\" style=\"display: block;\">" +
                            "<span class=\"title\">" + valor + "</span>" +
                            "<a class=\"secondary-content\" onclick=\"eliminarObjeto(\'" + data["descripcion"]["clase"] + "\',\'" + data["descripcion"]["id"] + "\'" + lista_borrar + ")\">" +
                                "<i class=\"material-icons red-text text-accent-1\">remove_circle</i>" +
                            "</a>" +
                        "</li>"); }
                    if(lista) { $("#" + lista).append("<option value=\"" + data["descripcion"]["id"] + "\">" + valor + " </option>"); $("select").material_select(); } }
                else{ Materialize.toast(data["descripcion"]["mensaje"], 1000); }
                $("#" + formulario)[0].reset(); },
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



//***************************************** Eliminación de objetos ******************************************\\
/*
    Realiza peticion para eliminar un registro de la base de datos, enviando la información necesaria mediante AJAX.
    Requiere: nombre de la clase a eliminar, id de registro.
*/
function eliminarObjeto(clase, id, lista) {
    if (confirm("Confirmar eliminación")) {
        if(id != "") {
            $.ajax( {
                data: { "clase_objeto": clase ,"id": id },
                type: "POST",
                url: "PHP/eliminar_objeto.php",
                beforeSend: function() { },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if(data["codigo_error"] == "0") {
                        Materialize.toast(data["descripcion"]["mensaje"],1000);
                        $("#" + id).remove();
                        if(lista) {
                            $(".DDLPREGUNTAS option[value=" + id + "]").remove();
                            $("select").material_select(); } }
                    else { Materialize.toast(data["descripcion"]["mensaje"],1000); } },
                error: function(){ Materialize.toast("Ocurrio un error",1000); } }); }
        else{ Materialize.toast("Objeto invalido",1000);
        return false; } } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************** Envio de correo electronico ****************************************\\
/*
    Realiza peticion para enviar un correo electronico al correo proporcionado/seleccionado.
    Requiere: dirección de correo electronico.
*/
function enviarMail(correo) {
    if (confirm("Confirmar envío")) {
        if(correo != "") {
            $.ajax( {
                data: { "correo": correo },
                type: "POST",
                url: "PHP/enviar_correo.php",
                beforeSend: function() { },
                success: function(response) {
                    var data = $.parseJSON(response);
                    if(data["codigo_error"] == "0") { Materialize.toast(data["descripcion"]["mensaje"],1000); }
                    else { Materialize.toast(data["descripcion"]["mensaje"],1000); } },
                error: function() { Materialize.toast("Ocurrio un error",1000); } } ); }
        else { Materialize.toast("Correo invalido",1000);
        return false; } } }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************** Filtra lista de egresados ****************************************\\
/*
    Realiza un filtro en la colección de alumnos por carrera y generación utilizando
    campos personalizados (-data).
*/
function filtrarAlumnos() {
    $("#coleccion_egresados li").each(function() {
        if($("#lista_carreras").val() == "all" && $("#lista_generacion").val() == "all") {
            $(this).css("display","block"); }
        else if($("#lista_carreras").val() == $(this).attr("data-carrera")&&$("#lista_generacion").val()==$(this).attr("data-generacion")){
            $(this).css("display","block"); }
        else if($("#lista_carreras").val() == "all" && $("#lista_generacion").val() == $(this).attr("data-generacion")) {
            $(this).css("display","block"); }
        else if($("#lista_carreras").val() == $(this).attr("data-carrera") && $("#lista_generacion").val() == "all") {
            $(this).css("display","block"); }
        else { $(this).css("display","none"); } } ); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************** Filtra lista de opciones ****************************************\\
/*
    Realiza un filtro en la colección de opciones en base a la pregunta seleccionada.
*/
function filtrarOpciones(coleccion, lista) {
    $("#" + coleccion + " li").each(function() {
        if($("#" + lista).val() == $(this).attr("data-pregunta")) {
            $(this).css("display","block"); }
        else { $(this).css("display","none"); } } ); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************** Creación de imagen/reporte ****************************************\\
/*
    Genera una imagen a partir de la pagina wee, permitiendo obtener un reporte.
*/
function setDDL() {
    $("#lista_carreras option[value=\"all\"]").prop("selected",true);
    $("#lista_generacion option[value=\"all\"]").prop("selected",true); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



//************************************** Creación de imagen/reporte ****************************************\\
/*
    Genera una imagen a partir de la pagina wee, permitiendo obtener un reporte.
*/
function printToImage() {
    html2canvas($("#body"), {
        onrendered: function(canvas) {
            window.open(canvas.toDataURL()); } }); }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////