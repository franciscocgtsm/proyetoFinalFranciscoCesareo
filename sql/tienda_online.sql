

USE tienda_online;

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla de categorías
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10,2),
    stock INT,
    imagen VARCHAR(255),
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabla de carrito
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    cantidad INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    total DECIMAL(10,2),
    estado VARCHAR(50),
    fecha DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de detalles de pedido
CREATE TABLE detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla de pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    metodo_pago VARCHAR(50),
    total_pago DECIMAL(10,2),
    estado VARCHAR(50),
    fecha DATETIME,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

-- Tabla de valoraciones
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    puntuacion INT,
    comentario TEXT,
    fecha DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar roles
INSERT INTO roles (nombre) VALUES ('comprador'), ('vendedor'), ('admin');

-- Insertar categorías
INSERT INTO categorias (nombre) VALUES 
('Ordenadores'),
('Placas base'),
('Teclados'),
('Ratones'),
('Móviles');

-- Insertar productos (3 de cada tipo)
INSERT INTO productos (nombre, descripcion, precio, stock, imagen, categoria_id) VALUES
('Ordenador Gaming', 'PC para juegos', 1200.00, 10, 'img/ordenador1.jpg', 1),
('Ordenador Oficina', 'PC para tareas básicas', 800.00, 15, 'img/ordenador2.jpg', 1),
('Ordenador Portátil', 'Laptop ligero', 1000.00, 8, 'img/ordenador3.jpg', 1),
('Placa base MSI', 'Socket AM4', 150.00, 20, 'img/placa1.jpg', 2),
('Placa base ASUS', 'Socket LGA1200', 160.00, 18, 'img/placa2.jpg', 2),
('Placa base Gigabyte', 'Socket AM5', 180.00, 10, 'img/placa3.jpg', 2),
('Teclado Mecánico', 'Teclado con switches rojos', 70.00, 25, 'img/teclado1.jpg', 3),
('Teclado Inalámbrico', 'Teclado bluetooth', 50.00, 30, 'img/teclado2.jpg', 3),
('Teclado RGB', 'Teclado iluminado', 90.00, 15, 'img/teclado3.jpg', 3),
('Ratón Gaming', 'Ratón con DPI ajustable', 60.00, 22, 'img/raton1.jpg', 4),
('Ratón Óptico', 'Ratón económico', 20.00, 40, 'img/raton2.jpg', 4),
('Ratón Inalámbrico', 'Ratón bluetooth', 35.00, 35, 'img/raton3.jpg', 4),
('Móvil Samsung', 'Galaxy S22', 900.00, 12, 'img/movil1.jpg', 5),
('Móvil Xiaomi', 'Redmi Note 12', 300.00, 20, 'img/movil2.jpg', 5),
('Móvil iPhone', 'iPhone 13', 1100.00, 10, 'img/movil3.jpg', 5);
