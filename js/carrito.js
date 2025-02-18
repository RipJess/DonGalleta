document.addEventListener('DOMContentLoaded', function () {
    inicializarEventos();
});

function inicializarEventos() {
    cargarCarrito();

}

function cargarCarrito() {
    fetch('./api/acciones-carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=cargar'
    })
        .then(response => response.json())
        .then(data => {
            let divCarrito = document.getElementById('productos-carrito');
            let noProductos = 0;
            divCarrito.innerHTML = "";
            data.forEach(producto => {
                let fila = `<div class="product-row" data-id="${producto.id_producto}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>${producto.nombre_producto}</h5>
                                    <p class="text-muted">${producto.sabores}</p>
                                    <p class="text-muted">$${producto.precio_unitario}</p>
                                </div>
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="decrementarCantidad(${producto.id_producto})">-</button>
                                    <input type="text" name="cantidadProducto" value="${producto.cantidad}" class="quantity-input" readonly>
                                    <button class="quantity-btn" onclick="incrementarCantidad(${producto.id_producto})">+</button>
                                </div>
                            </div>
                        </div>`;

                divCarrito.insertAdjacentHTML('beforeend', fila);
                noProductos += 1;
            });

            obtenerTotalCarrito();
            document.getElementById('num-items').textContent = `No. Items: ${noProductos}`;
        });
}

function actualizarCantidad(id_producto, cantidad) {
    fetch('./api/acciones-carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&id_producto=${id_producto}&cantidad=${cantidad}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarCarrito();
            } else {
                alert(data.message);
            }
        });
}

function vaciarCarrito() {
    if (confirm('¿Está seguro que desea vaciar el carrito?')) {
        fetch('./api/acciones-carrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=vaciar'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    cargarCarrito();
                } else {
                    alert('Error al vaciar el carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
    }
}

function obtenerTotalCarrito() {
    fetch('./api/acciones-carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=precioTotal'
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById("total-carrito").textContent = `$${parseFloat(data.total)}`;
        })
        .catch(error => console.error('Error:', error));
}


function incrementarCantidad(id_producto) {
    const input = document.querySelector(`.product-row[data-id="${id_producto}"] .quantity-input`);
    const nuevaCantidad = parseInt(input.value) + 1;
    actualizarCantidad(id_producto, nuevaCantidad);
}

function decrementarCantidad(id_producto) {
    const input = document.querySelector(`.product-row[data-id="${id_producto}"] .quantity-input`);
    const nuevaCantidad = parseInt(input.value) - 1;
    actualizarCantidad(id_producto, nuevaCantidad);
}

function validarPago() {
    let sucursal = document.querySelector('.delivery-box p').textContent.trim();
    if (sucursal === 'sin punto de entrega seleccionado') {
        alert('Por favor indique un punto de entrega.')
        return;
    } 
    
    let total = parseFloat(document.getElementById('total-carrito').textContent.substring(1));
    if (total < 20) {
        alert('Las compras deben ser mayores a $20.00.')
        return;
    }

    document.getElementById("realizarPago").setAttribute("data-bs-target", "#realizarCompra");
    document.getElementById("realizarPago").setAttribute("data-bs-toggle", "modal");
    document.getElementById("realizarPago").click();
}