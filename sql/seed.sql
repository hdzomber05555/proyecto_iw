-- Limpiamos usuarios previos para evitar errores
TRUNCATE TABLE usuarios;

-- Insertamos el usuario ADMIN
-- Usuario: admin
-- Contraseña: admin (El hash de abajo equivale a "admin") con el comando docker compose exec web php -r "echo password_hash('admin', PASSWORD_DEFAULT);"
INSERT INTO usuarios (username, password) 
VALUES ('admin', '$2y$10$xP5ZnO9YtrAWz7ytpF21z.upj1bmGtqLTfTleK.fivVR2qcoejmzK');

-- Items de prueba
INSERT INTO items (nombre, categoria, ubicacion, stock) VALUES 
('Portátil Dell', 'Informática', 'Almacen 1', 5),
('Ratón USB', 'Periféricos', 'Despacho 2', 10),
('Monitor 24"', 'Informática', 'Almacen 1', 3),
('Teclado Mecánico', 'Periféricos', 'Taller', 2);