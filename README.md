# Proyecto IAW: Gestor de Inventario

Nuestra práctica de Implantación de Aplicaciones Web. Es un gestor de inventario sencillo hecho en PHP sin frameworks, y usando Docker.

## Cómo arrancar el proyecto

Sigue estos pasos:

### 1. Clonar el repositorio

En la terminal tienes que copiar el siguiente comando:

```bash
git clone https://github.com/hdzomber05555/proyecto_iw.git
```

### 2. Levantar Docker

Abre la terminal en la carpeta del proyecto y escribe el comando :
<mark>IMPORTANTE TENER DOCKER INSTALADO</mark>

```bash
docker compose up -d
```

### 2. Cargar la Base de Datos

El proyecto viene vacío. Para meter las tablas y el usuario administrador:

    Entra en phpMyAdmin: http://localhost:8080/ (Usuario: root / Clave: root).

    Pincha en la base de datos inventario_bd.

    Ve a la pestaña Importar.

    Sube el archivo sql/seed.sql (esto mete los datos de prueba y el admin)


### 4. Entrar en la web

    Dirección: http://localhost:8000/public/

    IMPORTANTE Usuario: admin

    IMPORTANTE Contraseña: admin


![Login y creacion de cuenta](/imagenes/image.png)

![index con paginación y barra de busqueda ](/imagenes/image-1.png)

![Ver el producto](/imagenes/image-2.png)

![Preferencias del tema oscuro y claro](/imagenes/image-3.png)

![Primera captura rollback](/imagenes/image-4.png)

![segunda captura rollback](/imagenes/image-5.png)