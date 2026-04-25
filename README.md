# Pnyees — ERP Base para PYMES colombianas

Plataforma de e-commerce y gestión empresarial desarrollada en PHP (MVC) sobre XAMPP/MySQL, orientada a pequeñas y medianas empresas que operan en Colombia.

## Instalación

```bash
git clone https://gitlab.com/juaneslp93/pnyees.git
```

Configurar la conexión en `model/conexion.php` y ajustar la constante `RAIZ` en `model/setup.php` según el entorno.

Luego ejecutar las migraciones para crear el esquema de base de datos:

```bash
php migrate.php run
```

---

## Sistema de migraciones

El esquema de la base de datos vive en el código, no en el `.sql`. Cada cambio estructural se registra como un archivo de migración numerado en `model/migrations/`. La tabla `migrations` en MySQL lleva el historial de qué se ha ejecutado.

### Comandos

```bash
# Ver qué migraciones están pendientes o aplicadas
php migrate.php status

# Aplicar todas las migraciones pendientes
php migrate.php run

# Crear un archivo de migración en blanco para un nuevo cambio
php migrate.php make add_stock_to_productos

# Revertir la última migración aplicada
php migrate.php rollback

# Revertir las últimas N migraciones
php migrate.php rollback 3
```

### Estructura de un archivo de migración

```php
<?php
class Migration_002_add_stock_to_productos extends Migration
{
    public function up(mysqli $db): void
    {
        $db->query("ALTER TABLE productos ADD COLUMN stock INT NOT NULL DEFAULT 0");
    }

    public function down(mysqli $db): void
    {
        $db->query("ALTER TABLE productos DROP COLUMN stock");
    }
}
```

### Archivos del sistema

```
pnyees/
├── migrate.php                     ← CLI de entrada (php migrate.php <comando>)
└── model/
    ├── Migration.php               ← Clase base abstracta
    ├── MigrationRunner.php         ← Motor: detecta y ejecuta pendientes
    └── migrations/
        └── 001_initial_schema.php  ← Esquema base completo (16 tablas)
```

> Cada feature del roadmap debe ir acompañado de su propio archivo de migración. El `.sql` queda como referencia histórica únicamente.

---

## Estado actual del proyecto

### Lo que ya existe

| Módulo | Estado | Detalle |
|--------|--------|---------|
| E-Commerce — Tienda | Funcional | Catálogo, detalle de producto, carrito, checkout |
| Gestión de productos | Funcional | CRUD de productos con imagen, precio e impuesto |
| Órdenes de venta (compras del cliente) | Funcional | Registro con estados: aprobación, envío, proceso |
| Órdenes de compra (al proveedor) | Parcial | Estructura en BD presente, sin flujo completo |
| Gestión de usuarios / clientes | Funcional | Registro, login, recuperación de contraseña, roles |
| Panel administrativo | Funcional | Gestión de productos, usuarios, compras y órdenes |
| Métodos de pago | Parcial | Pago manual por transferencia bancaria con soporte de imagen |
| Generación de PDF | Funcional | Guías de envío con FPDF |
| Importación de datos | Funcional | Carga de departamentos y municipios desde Excel |
| Roles y permisos | Funcional | Admin, Usuario registrado, visitante de Tienda |
| Localización Colombia | Funcional | Departamentos, municipios, zona horaria América/Bogotá |

---

## Roadmap — Lo que falta para tener una base funcional comparable a Odoo

### Prioridad 1 — Núcleo operativo (sin esto el negocio no funciona bien)

#### 1.1 Control de inventario real
- [x] Agregar campo `stock` a la tabla `productos`
- [x] Descontar stock automáticamente al aprobar una venta
- [x] Registro de movimientos de inventario (entradas / salidas / ajustes)
- [x] Alertas de stock mínimo
- [x] Recepción de mercancía vinculada a orden de compra

#### 1.2 Pasarela de pagos
- [x] Integrar PSE o Wompi (más común en Colombia para PYMES)
- [x] Webhook de confirmación automática de pago
- [x] Eliminar dependencia del comprobante manual como única vía

#### 1.3 Gestión de proveedores
- [ ] Tabla `proveedores` con NIT, contacto, plazos de pago
- [ ] Precios de compra por proveedor y producto
- [ ] Ciclo completo de orden de compra: solicitud → aprobación → recepción → pago

---

### Prioridad 2 — Facturación electrónica y cumplimiento DIAN

#### 2.1 Datos fiscales del tercero
- [ ] Campo NIT / cédula con dígito de verificación
- [ ] Régimen tributario (responsable de IVA / no responsable / gran contribuyente)
- [ ] Responsabilidades DIAN (códigos O-13, O-15, etc.)
- [ ] Dirección fiscal completa (departamento, municipio, código postal)

#### 2.2 Tarifas de IVA diferenciadas
- [ ] Soporte para IVA 19%, 5%, 0% y excluido por producto
- [ ] Discriminación correcta en totales (base gravable + IVA separado)

#### 2.3 Retenciones
- [ ] Rete-Fuente por concepto y tarifa
- [ ] Rete-IVA (15% del IVA)
- [ ] Rete-ICA por municipio
- [ ] Aplicación automática según tipo de tercero

#### 2.4 Factura electrónica UBL 2.1
- [ ] Integración con un proveedor tecnológico homologado DIAN (Gosocket, Carvajal, etc.)
- [ ] Generación de CUFE y código QR
- [ ] Envío al adquiriente por correo electrónico
- [ ] Notas crédito y débito vinculadas a la factura original
- [ ] Numeración de resolución DIAN configurable

---

### Prioridad 3 — Contabilidad básica

#### 3.1 Plan Único de Cuentas (PUC Colombia)
- [ ] Tabla de cuentas contables basada en el PUC colombiano
- [ ] Clasificación: activo, pasivo, patrimonio, ingreso, gasto, costo

#### 3.2 Asientos contables automáticos
- [ ] Generación de asiento en doble partida al registrar una venta
- [ ] Generación de asiento al registrar una compra a proveedor
- [ ] Asiento de pago recibido / pago emitido

#### 3.3 Reportes contables
- [ ] Libro diario
- [ ] Libro mayor por cuenta
- [ ] Balance de prueba
- [ ] Estado de resultados (P&G)
- [ ] Balance general

#### 3.4 Conciliación bancaria
- [ ] Registro de cuentas bancarias con saldo
- [ ] Importación de extracto bancario (CSV/OFX)
- [ ] Conciliación manual de movimientos

---

### Prioridad 4 — Mejoras de e-commerce y ventas

- [ ] Variantes de producto (talla, color, capacidad)
- [ ] Categorías y subcategorías de productos
- [ ] Motor de descuentos y cupones
- [ ] Listas de precios por cliente o grupo
- [ ] Cotizaciones y presupuestos descargables en PDF
- [ ] Historial de precios por producto
- [ ] Seguimiento de envíos (número de guía por transportadora)

---

### Prioridad 5 — Infraestructura y seguridad

- [ ] Sanitización de entradas con sentencias preparadas en todos los modelos (algunos usan interpolación directa)
- [ ] Hasheo de contraseñas con `password_hash()` en todos los flujos de registro
- [ ] Control de sesiones con regeneración de ID tras login
- [ ] Logs de auditoría (quién hizo qué y cuándo)
- [ ] API REST para integraciones externas (apps móviles, marketplaces)
- [ ] Entorno de producción documentado (`.env`, HTTPS, cabeceras de seguridad)

---

## Arquitectura

```
pnyees/
├── model/          # Lógica de datos y acceso a BD (clases por entidad)
├── view/
│   ├── admin/      # Vistas del panel administrativo
│   ├── user/       # Vistas del cliente registrado
│   └── tienda/     # Vistas públicas de la tienda
├── controller/     # Controladores de flujo, validaciones, scripts
├── assets/         # CSS, JS, Bootstrap (SB Admin 2)
├── plugins/        # FPDF, importadores Excel
├── uploads/        # Imágenes de productos
└── pnyees.sql      # Esquema y datos de ejemplo
```

## Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 7.4+ (MVC sin framework) |
| Base de datos | MySQL / MariaDB |
| Frontend | Bootstrap 4, SB Admin 2, DataTables |
| PDF | FPDF 1.83 |
| Servidor local | XAMPP |
| Control de versiones | Git / GitLab |
