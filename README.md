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

## Variables de entorno
```
PORT=3000
DATABASE_URL=postgres://user:pass@localhost:5432/inventario
JWT_SECRET=clave_super_segura
```

## Scripts clave
```bash
npm run dev        # Desarrollo con recarga
npm run test       # Pruebas
npm run lint       # Estilo y calidad
npm run build      # Compilación frontend
```

## Modelo de datos (simplificado)
- Producto(id, nombre, sku, categoria_id, precio, stock, stock_minimo)
- Categoria(id, nombre)
- Movimiento(id, producto_id, tipo[entrada|salida], cantidad, fecha, nota)
- Proveedor(id, nombre, contacto)
- Usuario(id, correo, hash_password, rol)

## Endpoints (ejemplo)
```
GET    /api/productos
POST   /api/productos
PUT    /api/productos/:id
DELETE /api/productos/:id

POST   /api/movimientos
GET    /api/movimientos?k=SKU&tipo=entrada
```

## Ejemplo creación de producto
```bash
curl -X POST http://localhost:3000/api/productos \
    -H "Content-Type: application/json" \
    -d '{
        "nombre": "Mouse Óptico",
        "sku": "MOU-001",
        "categoria_id": 2,
        "precio": 9.99,
        "stock": 50,
        "stock_minimo": 10
    }'
```

## Lógica de alerta (pseudocódigo)
```js
if (producto.stock <= producto.stock_minimo) {
    enviarNotificacion(`Stock bajo: ${producto.nombre}`);
}
```

## Buenas prácticas
- Validar datos en backend y frontend
- Usar transacciones para movimientos
- Indexar campos SKU y nombre
- Registrar auditoría (usuario, timestamp)
- Copias de seguridad periódicas

## Pruebas (ejemplo Jest)
```js
test('crear producto', async () => {
    const res = await request(app)
        .post('/api/productos')
        .send({ nombre: 'Teclado', sku: 'TEC-123', precio: 15, stock: 20, stock_minimo: 5 });
    expect(res.status).toBe(201);
});
```

## Métricas sugeridas
- Rotación de inventario
- Tasa de quiebres de stock
- Tiempo promedio de reposición

## Despliegue con Docker (ejemplo)
```bash
docker compose up -d --build
```

## Seguridad
- HTTPS obligatorio
- Roles y permisos (admin, operador, consulta)
- Expiración de tokens
- Sanitizar entradas para evitar inyección

## Licencia
Uso interno. Adaptar a necesidades del proyecto.

## Próximos pasos
- Integración con facturación
- Dashboard analítico
- Soporte multialmacén

Fin.