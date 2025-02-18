document.addEventListener("DOMContentLoaded", function () {
    inicializarEventos();
});

function inicializarEventos() {
    funcionEliminarProducto();
    funcionActualizarProducto();
    funcionMostrarSabores();
    funcionMostrarPedido();
}

function funcionMostrarSabores() {
    const chBoxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');

    chBoxes.forEach((checkbox) => {
        checkbox.addEventListener('change', handleCB);
    });
}

function handleCB() {
    const chBoxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');
    const dpBtn = document.getElementById('multiSelectDropdown');
    mySelectedListItems = [];
    let mySelectedListItemsText = '';

    chBoxes.forEach((checkbox) => {
        if (checkbox.checked) {
            mySelectedListItems.push(checkbox.value);
            mySelectedListItemsText += checkbox.id + ', ';
        }
    });

    dpBtn.innerText = mySelectedListItems.length > 0 ? mySelectedListItemsText.slice(0, -2) : 'Select';
}

function funcionActualizarProducto() {
    let idProducto_actualizar = null;

    document.querySelectorAll("[data-bs-target='#actualizarProducto']").forEach(boton => {
        boton.addEventListener('click', function () {
            idProducto_actualizar = this.getAttribute("data-id");

            // Obtener los datos del producto a actualizar
            fetch('./api/acciones-admin.php?id=' + encodeURIComponent(idProducto_actualizar) + '&action=obtenerProducto')
                .then(response => response.json())
                .then(producto => {
                    document.getElementById("nombre_prod_act").value = producto.nombre;
                    document.getElementById("descripcion_prod_act").value = producto.descripcion;
                    document.getElementById("precio_prod_act").value = producto.precio;
                    document.getElementById("stock_prod_act").value = producto.stock;
                    document.getElementById("disponibilidad_prod_act").value = producto.disponibilidad;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    document.getElementById("guardarCambios").addEventListener("click", function () {
        let nombre = document.getElementById("nombre_prod_act").value.trim();
        let descripcion = document.getElementById("descripcion_prod_act").value.trim();
        let precio = document.getElementById("precio_prod_act").value.trim();
        let stock = document.getElementById("stock_prod_act").value.trim();
        let disponibilidad = document.getElementById("disponibilidad_prod_act").value;

        fetch('./api/acciones-admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '&action=actualizar' + '&id_producto=' + encodeURIComponent(idProducto_actualizar) + '&nombre=' + encodeURIComponent(nombre) + '&descripcion=' + encodeURIComponent(descripcion) + '&precio=' + encodeURIComponent(precio) + '&stock=' + encodeURIComponent(stock) + '&disponibilidad=' + encodeURIComponent(disponibilidad)
        })
            .then(response => response.json())
            .then(data => {
                mostrarMensaje(data.message, data.success ? 'success' : 'danger');
            })
            .catch(error => console.error('Error:', error));
    });
}

function funcionEliminarProducto() {
    let idProducto_eliminar = null;

    //obtener los id de los productos a eliminar
    document.querySelectorAll("[data-bs-target='#eliminarProducto']").forEach(boton => {
        boton.addEventListener('click', function () {
            idProducto_eliminar = this.getAttribute("data-id");
            // console.log(idProducto_eliminar);
        });
    });

    // Eliminar el producto
    document.getElementById("confirmaEliminar").addEventListener("click", function () {
        if (idProducto_eliminar) {
            fetch('./api/acciones-admin.php', {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_producto=' + encodeURIComponent(idProducto_eliminar) + '&action=eliminar'
            })
                .then(response => response.json())
                .then(data => {
                    mostrarMensaje(data.message, data.success ? 'success' : 'danger');
                })
                .catch(error => console.error('Error: ', error));
        }
    });
}

function funcionMostrarPedido() {
    let idPedido = null;
    document.querySelectorAll("[data-bs-target='#detallePedido']").forEach(boton => {
        boton.addEventListener('click', function () {
            idPedido = this.getAttribute("data-id");

            fetch('./api/acciones-admin.php?id_pedido=' + encodeURIComponent(idPedido) + '&action=obtenerPedido')
                .then(response => response.json())
                .then(pedido => {
                    document.getElementById("detalle_id").value = pedido.id_pedido;
                    document.getElementById("detalle_nombre").value = pedido.cliente;
                    document.getElementById("detalle_fecha").value = pedido.fecha_pedido;
                    document.getElementById("detalle_estado").value = pedido.estado;
                    document.getElementById("detalle_total").value = pedido.total;

                    let productos = document.getElementById("detalle_productos");
                    productos.innerHTML = "";

                    pedido.productos.forEach(producto => {
                        let item = `<tr>
                                        <td>${producto.nombre}</td>
                                        <td>${producto.cantidad}</td>
                                        <td>${producto.precio_unitario}</td>
                                        <td>${producto.subtotal}</td>
                                    </tr>`;

                        productos.insertAdjacentHTML('beforeend', item);
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    });
}

function mostrarMensaje(mensaje, tipo) {
    let alerta = document.getElementById('mensaje');
    alerta.textContent = mensaje;
    alerta.classList.add(`alert-${tipo}`);
    alerta.classList.remove('d-none');
    alerta.classList.add('show');
    setTimeout(() => {
        alerta.classList.remove("show");
        alerta.classList.add("d-none");
        alerta.classList.remove(`alert-${tipo}`);
    }, 3000);
}