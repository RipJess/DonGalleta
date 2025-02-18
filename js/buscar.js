document.addEventListener('DOMContentLoaded', function () {
    inicializarEventos();
});

function inicializarEventos() {
    mostrarProductos();
    // agregarAlCarrito();
}

function validaUsuario() {
    if (document.getElementById('usuario-aut') === null) {
        return false;
    }
    return true;
}

function agregarAlCarrito(idProducto_agregar) {
    if (!validaUsuario()) {
        let alerta = document.getElementById('noUsuario');
        alerta.classList.remove('d-none');
        alerta.classList.add('show');
        setTimeout(() => {
            alerta.classList.remove("show");
            alerta.classList.add("d-none");
        }, 3000);
        return;
    }

    fetch('./api/acciones-carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=agregar&id_producto=' + encodeURIComponent(idProducto_agregar)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let alerta = document.getElementById('mostrarAlerta');
                alerta.classList.remove('d-none');
                alerta.classList.add('show');
                setTimeout(() => {
                    alerta.classList.remove("show");
                    alerta.classList.add("d-none");
                }, 3000);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errorcito', error));
}

function mostrarProductos() {
    let busqueda = "";
    if (document.getElementById("busqueda") === null) {
        busqueda = "all";
    } else {
        busqueda = document.getElementById("busqueda").value;
    }

    let orden = document.getElementById("orden").value;

    if (orden === '') {
        orden = "nombre_asc";
    }

    fetch(`./api/buscar-productos.php?action=mostrar&orden=${orden}&busqueda=${busqueda}`)
        .then(response => response.json())
        .then(data => {
            let resultadosDiv = document.getElementById("resultados");
            resultadosDiv.innerHTML = "";
            data.forEach(producto => {
                resultadosDiv.innerHTML += `<div class='casilla'>
                                            <img class='img-fluid' style='border-radius: 25px;' width='200' src='img/rectangle-26.png'>
                                            <div class='descripcion'>
                                                <p class='nombre-galleta'>${producto.nombre_producto}</p>
                                                <p class='sabor'>${producto.sabores}</p>
                                                <div class='precio'>
                                                    <img src='img/precio.svg'>
                                                    <p class='nombre-galleta' style='font-size: 16px; margin-bottom: 0px;'>${producto.precio}</p>
                                                </div>
                                                <br>
                                                <button type='button' id='agregarCarrito' class='btn btn-outline-danger btn-sm btn-accion' onclick='agregarAlCarrito(${producto.id_producto})'>Agregar al carrito</button>
                                            </div>
                                        </div>`;
            });
        })
        .catch(error => console.error('Error:', error));
}
