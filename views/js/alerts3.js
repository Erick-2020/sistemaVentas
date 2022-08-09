// alert("hola mundooo");
// ENVIAR FORMULARIOS VIA AJAX

// SELECCIONAR TODOS LOS FORMUARIOS QUE CORRESPONDAN A DICHA CLASE
const formuAjax = document.querySelectorAll(".FormularioAjax");

// PARA CUANDO ENVIEMOS EL FORMULARIO NO SE REDIRECIONE AL ARCHIVO QUE SE ESTE INDICANDO
function sendFormuAjax(e){
    e.preventDefault();

    // CONTIENE DATOS DEL FORMULARIO con la funcion para el ARRAY DE DATOS,
    let data = new FormData(this);


    // OBTENER EL METODO PARA ENVIAR LOS DADTOS DEL FORMULARIO
    let method = this.getAttribute("method");
    // obtener en atributo del valor del metodo action del fomrulario
    let action = this.getAttribute("action");

    let type = this.getAttribute("data-form");

    let encabezados = new Headers();

    // configuracion de lo que se va aenviar en la funcion de fetch
    let config = {
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body:data
    };

    let alertText;

    if(type === "save"){
        alertText = "Los registros seran guardados correctamente en el sistema";
    }else if(type === "delete"){
        alertText = "Los registros seran eliminados correctamente del sistema";
    }else if(type === "update"){
        alertText = "Los registros seran actualizados correctamente en el sistema";
    }else if(type === "search"){
        alertText = "Un momento por favor";
    // Eliminar un registro de prestamos
    }else if(type === "loans"){
        alertText = "Desea eliminar el registro seleccionado";
    }else{
        alertText = "Error en el sistema";
        // error;
    }

    Swal.fire({
        title: 'Excelente',
        text: alertText,
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then((result)=>{
        if(result.value){
            // alert("error");
            // ENVIAR DATOS
            fetch(action, config)
            // parsear o colocar los datos en formato json
            .then(respuesta => {
                return respuesta.json()
            })
            // retornar las alertas correspondientes
            .then(respuesta => {
                return alerts(respuesta);
            })
        }
    });

    // let error = Swal.fire({
    //     icon: 'error',
    //     title: '',
    //     text: alertText,
    //     confirmButtonText: "Aceptar"
    // });

};
formuAjax.forEach(formularios => {
    formularios.addEventListener("submit", sendFormuAjax);
});

function alerts(alert){
    if(alert.Alerta === "simple"){
        Swal.fire({
            title: alert.title,
            text: alert.message,
            icon: alert.type,
            confirmButtonText: "Aceptar"
        })
    }else if(alert.Alerta === "recargar"){
        Swal.fire({
            title: alert.title,
            text: alert.message,
            icon: alert.type,
            confirmButtonText: "Aceptar"
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        })
    }else if(alert.Alerta === "limpiar"){
        Swal.fire({
            title: alert.title,
            text: alert.message,
            icon: alert.type,
            confirmButtonText: "Aceptar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(".FormularioAjax").reset();
            }
        })
    }else if(alert.Alerta === "redireccionar"){
        window.location.href = alert.url;
    }
}