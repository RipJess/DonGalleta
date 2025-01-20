CREATE TABLE `Usuarios` (
  `id_usuario` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('cliente','administrador'),
  `fecha_registro` TIMESTAMP
);

CREATE TABLE `Productos` (
  `id_producto` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT,
  `precio` DECIMAL(10,2) NOT NULL,
  `imagen` VARCHAR(255),
  `id_sabor` int,
  `disponibilidad` BOOLEAN DEFAULT true
);

CREATE TABLE `Pedidos` (
  `id_pedido` INT PRIMARY KEY AUTO_INCREMENT,
  `id_usuario` INT,
  `fecha_pedido` TIMESTAMP NOT NULL,
  `estado` ENUM('pendiente','procesando','completado','cancelado'),
  `total` DECIMAL(10,2) NOT NULL
);

CREATE TABLE `Detalles_pedido` (
  `id_detalle` INT PRIMARY KEY AUTO_INCREMENT,
  `id_pedido` INT,
  `id_producto` INT,
  `cantidad` INT,
  `precio_unitario` DECIMAL(10,2),
  `subtotal` DECIMAL(10,2)
);

CREATE TABLE `Tickets` (
  `id_ticket` INT PRIMARY KEY AUTO_INCREMENT,
  `id_pedido` INT NOT NULL,
  `id_sucursal` int,
  `codigo_ticket` VARCHAR(50) UNIQUE,
  `fecha_generacion` TIMESTAMP
);

CREATE TABLE `Sucursales` (
  `id_sucursal` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `direccion` TEXT,
  `latitud` DECIMAL(10,8),
  `longitud` DECIMAL(11,8),
  `telefono` VARCHAR(20)
);

CREATE TABLE `Sabores` (
  `id_sabor` INT PRIMARY KEY AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` TEXT
);

CREATE TABLE `Pagos` (
  `id_pago` INT PRIMARY KEY AUTO_INCREMENT,
  `id_pedido` INT NOT NULL,
  `metodo_pago` ENUM('paypal','transferencia'),
  `referencia` VARCHAR(255) UNIQUE,
  `monto` DECIMAL(10,2),
  `fecha_pago` TIMESTAMP
);

ALTER TABLE `Pedidos` ADD FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`);

ALTER TABLE `Detalles_pedido` ADD FOREIGN KEY (`id_pedido`) REFERENCES `Pedidos` (`id_pedido`);

ALTER TABLE `Detalles_pedido` ADD FOREIGN KEY (`id_producto`) REFERENCES `Productos` (`id_producto`);

ALTER TABLE `Tickets` ADD FOREIGN KEY (`id_pedido`) REFERENCES `Pedidos` (`id_pedido`);

ALTER TABLE `Pagos` ADD FOREIGN KEY (`id_pedido`) REFERENCES `Pedidos` (`id_pedido`);

ALTER TABLE `Productos` ADD FOREIGN KEY (`id_sabor`) REFERENCES `Sabores` (`id_sabor`);

ALTER TABLE `Tickets` ADD FOREIGN KEY (`id_sucursal`) REFERENCES `Sucursales` (`id_sucursal`);