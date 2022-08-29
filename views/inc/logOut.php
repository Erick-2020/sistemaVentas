<script>
    let btnClose = document.querySelector(".btn-exit-system");

    // ESCCHAR UN EVENTO CLICK Y EJECUTAR UNA FUNCION PREVINIENDO LA MISMA
    btnClose.addEventListener('click', function(e){
        e.preventDefault();

        Swal.fire({
			title: 'Estas seguro de salir del sistema?',
			text: "La sesion actual se cerrara correctamente",
            icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, Salir!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
                let url = '<?php echo SERVERURL; ?>ajax/loginAjax.php';
                let token = '<?php echo $loginController->encryption($_SESSION['token_sv']); ?>';
                let usuario = '<?php echo $loginController->encryption($_SESSION['usuario_sv']); ?>';

                // CREAR LOS DATOS A PARTIR DE LAS VARIABLES CON FETCH
                let data = new FormData();
                // AGREGAMOS LOS VALORES
                data.append("token", token);
                data.append("usuario", usuario);

                // ENVIAR DATOS
                fetch(url, {
                    method: 'POST',
                    body: data
                })
                // parsear o colocar los datos en formato json
                .then(response => {
                    return response.json()
                })
                // retornar las alertas correspondientes
                .then(response => {
                    return alerts(response);
                });
			}
		});
    });
</script>