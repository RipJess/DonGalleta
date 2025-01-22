// Datos vacíos inicialmente (sin productos ni pedidos)
let productos = [];
const pedidos = [];

// Referencias a elementos HTML
const tablaProductos = document.querySelector("#tabla-productos tbody");
const tablaPedidos = document.querySelector("#tabla-pedidos tbody");
const modalProducto = document.getElementById("modal-producto");
const btnAgregarProducto = document.getElementById("btn-agregar-producto");
const cerrarModal = document.getElementById("cerrar-modal");
const formProducto = document.getElementById("form-producto");
const nombreInput = document.getElementById("nombre");
const descripcionInput = document.getElementById("descripcion");
const precioInput = document.getElementById("precio");
const cantidadInput = document.getElementById("cantidad");

// Referencias a variables globales
let editando = false;  // Controla si estamos editando un producto

// Mostrar productos en la tabla
function mostrarProductos() {
  tablaProductos.innerHTML = "";  // Limpiar tabla
  if (productos.length === 0) {
    tablaProductos.innerHTML = "<tr><td colspan='5'>No hay productos disponibles.</td></tr>";
  } else {
    productos.forEach(producto => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${producto.nombre}</td>
        <td>${producto.descripcion}</td>
        <td>$${producto.precio.toFixed(2)} MXN</td>
        <td>${producto.cantidad}</td>
        <td>
          <button onclick="editarProducto(${producto.id})">Editar</button>
          <button onclick="eliminarProducto(${producto.id})">Eliminar</button>
        </td>
      `;
      tablaProductos.appendChild(tr);
    });
  }
}

// Mostrar pedidos en la tabla
function mostrarPedidos() {
  tablaPedidos.innerHTML = "";  // Limpiar tabla
  if (pedidos.length === 0) {
    tablaPedidos.innerHTML = "<tr><td colspan='5'>No hay pedidos recientes.</td></tr>";
  } else {
    pedidos.forEach(pedido => {
      const tr = document.createElement("tr");

      // Crear la lista de productos en el pedido
      const productosPedido = pedido.productos.map(producto => {
        return `
          <p>${producto.nombre} - ${producto.cantidad} x $${producto.precio.toFixed(2)} MXN</p>
        `;
      }).join('');

      // Calcular el total del pedido
      const totalPedido = pedido.productos.reduce((total, producto) => {
        return total + (producto.precio * producto.cantidad);
      }, 0);

      tr.innerHTML = `
        <td>${pedido.cliente}</td>
        <td>${pedido.fecha}</td>
        <td>${pedido.estado}</td>
        <td>${productosPedido}</td>
        <td>$${totalPedido.toFixed(2)} MXN</td>
      `;
      tablaPedidos.appendChild(tr);
    });
  }
}

// Agregar un nuevo producto
btnAgregarProducto.addEventListener("click", () => {
  editando = false;  // No estamos editando
  modalProducto.style.display = "flex";
  nombreInput.value = "";
  descripcionInput.value = "";
  precioInput.value = "";
  cantidadInput.value = "";
});

// Cerrar el modal
cerrarModal.addEventListener("click", () => {
  modalProducto.style.display = "none";
});

// Agregar o actualizar producto
formProducto.addEventListener("submit", (e) => {
  e.preventDefault();  // Prevenir el comportamiento por defecto del formulario (recargar la página)

  const nuevoProducto = {
    nombre: nombreInput.value,
    descripcion: descripcionInput.value,
    precio: parseFloat(precioInput.value),
    cantidad: parseInt(cantidadInput.value)
  };

  if (editando) {
    // Si estamos editando, actualizamos el producto
    const index = productos.findIndex(p => p.id === nuevoProducto.id);
    productos[index] = nuevoProducto;
  } else {
    // Si no estamos editando, agregamos un nuevo producto
    nuevoProducto.id = productos.length + 1;  // Asignar un nuevo ID
    productos.push(nuevoProducto);
  }

  mostrarProductos();  // Volver a mostrar los productos actualizados
  modalProducto.style.display = "none";  // Cerrar el modal
});

// Editar un producto
function editarProducto(id) {
  const producto = productos.find(p => p.id === id);

  nombreInput.value = producto.nombre;
  descripcionInput.value = producto.descripcion;
  precioInput.value = producto.precio;
  cantidadInput.value = producto.cantidad;

  editando = true;  // Marcamos que estamos editando
  modalProducto.style.display = "flex";
}

// Eliminar un producto
function eliminarProducto(id) {
  const productoIndex = productos.findIndex(p => p.id === id);
  productos.splice(productoIndex, 1);
  mostrarProductos();
}

// Inicializar las tablas
mostrarProductos();
mostrarPedidos();
