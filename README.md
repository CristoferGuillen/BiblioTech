# BiblioTech

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=flat-square&logo=livewire&logoColor=white)
![Flux UI](https://img.shields.io/badge/Flux_UI-2.9-111827?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=flat-square&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=flat-square&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

<p align="center">
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=php,laravel,mysql,tailwind,vite,js,git&theme=light" alt="Tecnologías utilizadas en BiblioTech" />
  </a>
</p>

**BiblioTech** es una aplicación web full-stack para la gestión de bibliotecas. El sistema permite administrar libros, categorías, usuarios, préstamos, devoluciones y renovaciones desde una plataforma construida con **Laravel**, **Livewire**, **Flux UI**, **Blade**, **Tailwind CSS** y **MySQL**.

El proyecto está diseñado para digitalizar procesos comunes de una biblioteca, centralizando el catálogo de libros, el control de copias disponibles, el registro de préstamos y la separación de funcionalidades por roles.

> Nota: La interfaz de la aplicación está actualmente en español, ya que el proyecto está orientado a entornos bibliotecarios hispanohablantes.

## Tabla de Contenidos

- [Tecnologías](#tecnologías)
- [Descripción General](#descripción-general)
- [Características Principales](#características-principales)
- [Roles del Sistema](#roles-del-sistema)
- [Modelo de Dominio](#modelo-de-dominio)
- [Requisitos Previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Base de Datos](#base-de-datos)
- [Ejecución Local](#ejecución-local)
- [Credenciales de Prueba](#credenciales-de-prueba)
- [Comandos Útiles](#comandos-útiles)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Documentación Técnica](#documentación-técnica)
- [Autor](#autor)
- [Licencia](#licencia)

## Tecnologías

- **PHP 8.2+**
- **Laravel 12**
- **Laravel Fortify**
- **Laravel Sanctum**
- **Livewire 3**
- **Livewire Volt**
- **Flux UI**
- **Blade**
- **Tailwind CSS 4**
- **Vite 7**
- **MySQL 8.0+**
- **Eloquent ORM**
- **Composer**
- **npm**

## Descripción General

BiblioTech fue desarrollado como una solución web para apoyar la gestión diaria de una biblioteca. Su objetivo es reemplazar procesos manuales o dispersos por una plataforma centralizada, organizada y accesible desde el navegador.

La aplicación incluye autenticación, control de acceso basado en roles, gestión de libros, relación con categorías, administración de préstamos, devolución de libros, renovación de préstamos, soft deletes para mantener trazabilidad y dashboards adaptados al tipo de usuario.

El sistema sigue la arquitectura **MVC** de Laravel, separando la lógica de negocio en controladores, la persistencia de datos en modelos Eloquent y la presentación en vistas Blade con componentes Livewire y Flux UI.

## Características Principales

### Autenticación y autorización

- Registro e inicio de sesión de usuarios.
- Autenticación basada en sesiones de Laravel.
- Integración con Laravel Fortify.
- Control de acceso basado en roles.
- Middleware personalizado `CheckRole`.
- Redirección de usuarios según su rol.
- Rutas protegidas para usuarios autenticados.
- Vista de acceso no autorizado.

### Gestión de usuarios

- Registro de usuarios del sistema.
- Roles disponibles:
  - Administrador.
  - Bibliotecario.
  - Miembro.
- Campo de teléfono opcional.
- Soft deletes para mantener trazabilidad.
- Métodos auxiliares para validar roles.
- Relación entre usuarios y préstamos.
- Historial de préstamos por usuario.

### Gestión de libros

- Registro de libros.
- Edición de información bibliográfica.
- Visualización de detalle de libros.
- Eliminación lógica mediante soft deletes.
- Restauración de libros eliminados.
- Validación de ISBN único.
- Gestión de título, autor, año de publicación, categoría, copias disponibles y estado.
- Estados de libro:
  - Disponible.
  - No disponible.
- Paginación de registros.
- Relación entre libros y categorías.
- Manejo de libros eliminados dentro del historial de préstamos.

### Gestión de categorías

- Modelo de categorías para clasificar libros.
- Nombre único por categoría.
- Descripción opcional.
- Relación entre categoría y libros.
- Base estructural para organizar el catálogo bibliográfico.

### Gestión de préstamos

- Registro de préstamos de libros.
- Asociación entre préstamo, usuario y libro.
- Validación de disponibilidad antes de crear un préstamo.
- Disminución automática de copias disponibles al prestar un libro.
- Fecha de préstamo.
- Fecha estimada de devolución.
- Estados de préstamo:
  - En curso.
  - Devuelto.
  - Atrasado.
- Registro de devolución.
- Incremento automático de copias disponibles al devolver un libro.
- Renovación de préstamos por 7 días adicionales.
- Manejo de préstamos asociados a libros eliminados lógicamente.

### Dashboard de bibliotecario

- Vista personalizada para bibliotecarios.
- Total de libros registrados.
- Total de préstamos.
- Conteo de préstamos en curso.
- Conteo de préstamos atrasados.
- Conteo de libros disponibles.
- Conteo de libros no disponibles.
- Traducciones de roles y estados para mostrar información en español.
- Acceso rápido a la gestión operativa de la biblioteca.

### Panel administrativo

- Ruta base para dashboard de administrador.
- Ruta base para gestión de usuarios.
- Ruta base para configuración del sistema.
- Ruta base para reportes.
- Separación de acceso para usuarios con rol administrador.
- Base preparada para extender funcionalidades administrativas.

### Panel de miembro

- Ruta base para dashboard de miembro.
- Ruta base para consulta de préstamos personales.
- Ruta base para futuras reservaciones.
- Separación de acceso para usuarios con rol miembro.

### Seguridad y validaciones

- Protección CSRF en formularios.
- Hashing automático de contraseñas.
- Prevención de SQL Injection mediante Eloquent ORM.
- Escapado automático de datos en vistas Blade.
- Middleware de autorización por roles.
- Validación de campos obligatorios.
- Validación de ISBN único.
- Validación de años de publicación.
- Validación de existencia de usuarios y libros antes de crear préstamos.
- Restricción de acciones según rol.

## Roles del Sistema

BiblioTech utiliza roles para separar responsabilidades y permisos dentro de la aplicación.

| Rol | Descripción |
| --- | --- |
| `admin` | Tiene acceso a las rutas administrativas, configuración, usuarios y reportes. |
| `librarian` | Puede gestionar libros, préstamos, devoluciones, renovaciones y el dashboard operativo. |
| `member` | Puede acceder a su dashboard personal, consultar sus préstamos y futuras reservaciones. |

## Modelo de Dominio

El sistema está organizado alrededor de las principales entidades de una biblioteca.

| Entidad | Propósito |
| --- | --- |
| `User` | Representa a los usuarios del sistema, sus datos de autenticación, rol y préstamos. |
| `Book` | Representa los libros del catálogo bibliográfico. |
| `Category` | Clasifica los libros por tipo, tema o área. |
| `Loan` | Representa un préstamo de un libro a un usuario. |

### Relaciones principales

- Un `User` puede tener muchos `Loan`.
- Un `Book` pertenece a una `Category`.
- Una `Category` puede tener muchos `Book`.
- Un `Book` puede estar asociado a muchos `Loan`.
- Un `Loan` pertenece a un `User`.
- Un `Loan` pertenece a un `Book`.

## Requisitos Previos

Antes de instalar el proyecto, asegúrate de tener instalado:

- PHP 8.2 o superior.
- Composer.
- MySQL 8.0 o superior.
- Node.js 18 o superior.
- npm.
- Git.

Extensiones PHP recomendadas para ejecutar Laravel con MySQL:

```ini
extension=bcmath
extension=ctype
extension=curl
extension=fileinfo
extension=json
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=tokenizer
extension=xml
extension=zip
```

## Instalación

Clona el repositorio:

```bash
git clone https://github.com/CristoferGuillen/BiblioTech.git
```

Entra a la carpeta del proyecto:

```bash
cd BiblioTech
```

Instala las dependencias de PHP:

```bash
composer install
```

Instala las dependencias de JavaScript:

```bash
npm install
```

Copia el archivo de entorno:

```bash
cp .env.example .env
```

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

## Configuración

Edita el archivo `.env` y configura los valores principales de la aplicación:

```env
APP_NAME=BiblioTech
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Configura la conexión a MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bibliotech
DB_USERNAME=root
DB_PASSWORD=your_password
```

Configura las sesiones:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Crea una base de datos llamada `bibliotech` antes de ejecutar las migraciones.

Puedes crearla desde MySQL con:

```sql
CREATE DATABASE bibliotech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Base de Datos

Ejecuta las migraciones:

```bash
php artisan migrate
```

Carga los datos iniciales:

```bash
php artisan db:seed
```

También puedes recrear la base de datos y cargar los seeders en un solo comando:

```bash
php artisan migrate:fresh --seed
```

> Advertencia: `migrate:fresh --seed` elimina las tablas existentes, vuelve a ejecutar las migraciones y carga nuevamente los datos de prueba.

### Tablas principales

| Tabla | Descripción |
| --- | --- |
| `users` | Usuarios del sistema, roles, credenciales y soft deletes. |
| `categories` | Categorías utilizadas para clasificar libros. |
| `books` | Catálogo de libros, ISBN, autor, año, copias y estado. |
| `loans` | Registro de préstamos, devoluciones, fechas y estados. |
| `sessions` | Sesiones de Laravel almacenadas en base de datos. |

## Ejecución Local

Puedes ejecutar el entorno de desarrollo completo con:

```bash
composer run dev
```

También puedes ejecutar Laravel y Vite por separado.

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Luego abre la aplicación en:

```text
http://localhost:8000
```

Rutas principales:

```text
http://localhost:8000
http://localhost:8000/login
http://localhost:8000/register
http://localhost:8000/dashboard
http://localhost:8000/librarian/dashboard
http://localhost:8000/admin/dashboard
http://localhost:8000/member/dashboard
```

## Credenciales de Prueba

El seeder principal crea un usuario base de prueba.

| Rol | Email | Contraseña |
| --- | --- | --- |
| Miembro | `test@example.com` | `password` |

> Nota: El rol por defecto de los usuarios es `member`. Para probar las rutas de `admin` o `librarian`, puedes actualizar el campo `role` manualmente en la base de datos durante el desarrollo.

Ejemplo:

```sql
UPDATE users SET role = 'admin' WHERE email = 'test@example.com';
```

O para bibliotecario:

```sql
UPDATE users SET role = 'librarian' WHERE email = 'test@example.com';
```

## Comandos Útiles

Ejecutar migraciones:

```bash
php artisan migrate
```

Ejecutar seeders:

```bash
php artisan db:seed
```

Recrear la base de datos con datos iniciales:

```bash
php artisan migrate:fresh --seed
```

Iniciar el servidor local:

```bash
php artisan serve
```

Ejecutar Vite:

```bash
npm run dev
```

Compilar assets para producción:

```bash
npm run build
```

Ejecutar el entorno completo de desarrollo:

```bash
composer run dev
```

Ejecutar tests:

```bash
php artisan test
```

Ejecutar el script de pruebas definido en Composer:

```bash
composer test
```

Ejecutar Laravel Pint:

```bash
./vendor/bin/pint
```

## Estructura del Proyecto

```text
BiblioTech/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── BookController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LibrarianDashboardController.php
│   │   │   └── LoanController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   │
│   ├── Livewire/
│   ├── Models/
│   │   ├── Book.php
│   │   ├── Category.php
│   │   ├── Loan.php
│   │   └── User.php
│   └── Providers/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_cache_table.php
│   │   ├── create_jobs_table.php
│   │   ├── create_categories_table.php
│   │   ├── create_books_table.php
│   │   ├── create_loans_table.php
│   │   └── create_sessions_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── books/
│       ├── components/
│       ├── librarian/
│       ├── livewire/
│       ├── loans/
│       └── member/
│
├── routes/
│   ├── console.php
│   └── web.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── DIAGRAMAS.md
├── TECHNICAL_DOCUMENTATION.md
└── vite.config.js
```

## Documentación Técnica

El repositorio incluye documentación adicional en:

```text
TECHNICAL_DOCUMENTATION.md
DIAGRAMAS.md
```

Estos documentos describen con mayor detalle:

- Arquitectura general del proyecto.
- Modelo de dominio.
- Relaciones entre entidades.
- Flujos principales del sistema.
- Diagramas visuales.
- Estructura técnica.
- Organización de módulos.
- Consideraciones de seguridad y autorización.

## Recomendaciones Técnicas

Antes de considerar el repositorio como una versión final, se recomienda revisar:

- Remover `vendor/` y `node_modules/` del control de versiones.
- Confirmar que `.gitignore` excluya dependencias generadas.
- Corregir la ruta `books.updateCopies` o implementar el método correspondiente en `BookController`.
- Revisar el conteo de libros no disponibles en el dashboard de bibliotecario.
- Agregar seeders completos para roles `admin`, `librarian` y `member`.
- Completar las vistas administrativas y de miembros si aún están como base inicial.
- Ejecutar pruebas con `php artisan test`.
- Verificar compilación de assets con `npm run build`.

## Autor

Desarrollado por **Cristofer Guillen**.

- GitHub: [@CristoferGuillen](https://github.com/CristoferGuillen)
- Repositorio: [BiblioTech](https://github.com/CristoferGuillen/BiblioTech)

## Licencia

Este proyecto está disponible bajo la licencia **MIT**.
