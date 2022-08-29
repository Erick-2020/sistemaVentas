<script>
    // BUSCAR Y AGREGAR UN CLIENTE EN LA VISTA DE PRESTAMOS
    function searchClient(){
        let inputClient = document.querySelector('#input_cliente').value;

        inputClient = inputClient.trim();

        if(inputClient != ""){
            let data = new FormData();
            data.append("buscar_cliente", inputClient);

            fetch("<?php echo SERVERURL; ?>ajax/prestamosAjax.php", {
                method:'POST',
                body: data
            })
            .then(response => response.text())
            .then(response => {
                let ClientTable = document.querySelector('#tabla_clientes');
                ClientTable.innerHTML = response;
            });
        }else{
            Swal.fire({
                title: "Ocurrio un error",
                text: "Debes introducir los campos necesarios para buscar el cliente",
                icon: 'error',
                confirmButtonText: "Aceptar"
            })
        }
    }

    function addClient(id){
        // OCULTAMOS LA VENTANA MODAL
        $('#ModalCliente').modal('hide');

        Swal.fire({
        title: 'Seguro',
        text: 'Estas seguro que quieres agregar el cliente?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Agregar",
        cancelButtonText: "Cancelar"
        }).then((result)=>{
            if(result.value){
                let data = new FormData();
                data.append("id_agregar_cliente", id);

                fetch("<?php echo SERVERURL; ?>ajax/prestamosAjax.php", {
                    method:'POST',
                    body: data
                })
                .then(response => response.json())
                .then(response => {
                    return alerts(response);
                });
            }else{
                $('#ModalCliente').modal('show');
            }
        });
    }

    function searchItem(){
        let inputItem = document.querySelector('#input_item').value;

        inputItem = inputItem.trim();

        if(inputItem != ""){
            let data = new FormData();
            data.append("buscar_item", inputItem);

            fetch("<?php echo SERVERURL; ?>ajax/prestamosAjax.php", {
                method:'POST',
                body: data
            })
            .then(response => response.text())
            .then(response => {
                let itemTable = document.querySelector('#tabla_items');
                itemTable.innerHTML = response;
            });
        }else{
            Swal.fire({
                title: "Ocurrio un error",
                text: "Debes introducir los datos necesarios para buscar el producto",
                icon: 'error',
                confirmButtonText: "Aceptar"
            })
        }
    }

    function addItem(id){
        // OCULTAMOS LA VENTANA MODAL
        $('#ModalItem').modal('hide');

        // MOSTRAMOS LA VENTANA MODAL PARA AGREGAR ITEM
        $('#ModalAgregarItem').modal('show');

        // SE LE AGREGAR EL ID DEL INPUT SELECCIONADO
        document.querySelector('#id_agregar_item').setAttribute("value", id);

    }

    function modalSearchItem(){
        $('#ModalAgregarItem').modal('hide');
        $('#ModalItem').modal('show');
    }
</script>