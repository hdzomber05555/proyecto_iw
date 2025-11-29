-- Creamos la tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Creamos la tabla inventario
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    ubicacion VARCHAR(50),
    stock INT DEFAULT 0
);

-- Crear la tabla de audiorio de borrados
CREATE TABLE IF NOT EXISTS auditoria_borrados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_datos TEXT NOT NULL,
    usuario_id INT,
    fecha_borrado DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Limpiar datos antiguos 
TRUNCATE TABLE usuarios;
TRUNCATE TABLE items;

-- 5. INSERTAR EL USUARIO ADMIN
-- Usuario: admin / Contraseña: admin
INSERT INTO usuarios (username, password) 
VALUES ('admin', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1nERZOe');

-- 6. INSERTAR ÍTEMS DE PRUEBA
INSERT INTO items (nombre, categoria, ubicacion, stock) VALUES 
('Portátil Dell', 'Informática', 'Almacén 1', 5),
('Ratón USB', 'Periféricos', 'Despacho 2', 10),
('Monitor 24"', 'Informática', 'Almacén 1', 3),
('Teclado Mecánico', 'Periféricos', 'Sala Servidores', 2);