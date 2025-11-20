# Gestor de Inventario

Sistema sencillo para controlar productos, existencias y movimientos en tiempo real.

## Características
- Alta, edición y baja de productos
- Categorías y proveedores
- Control de stock mínimo y alertas
- Registro de entradas y salidas (kardex)
- Búsqueda y filtrado avanzado
- Exportación CSV / JSON
- API REST

## Tecnologías (ejemplo)
- Backend: Node.js + Express
- Base de datos: PostgreSQL
- Autenticación: JWT
- Frontend: React / Vue
- Docker para despliegue

## Instalación (ejemplo)
```bash
git clone https://github.com/usuario/gestor-inventario.git
cd gestor-inventario
cp .env.example .env
npm install
npm run migrate
npm run seed
npm start
```

