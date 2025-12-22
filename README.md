# 📚 BiblioTech

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=flat&logo=livewire)](https://livewire.laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistema de Gestión de Biblioteca Full-Stack desarrollado con Laravel 12, Livewire y Flux UI.

---

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración](#️-configuración)
- [Uso](#-uso)
- [Roles y Permisos](#-roles-y-permisos)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Estado del Desarrollo](#-estado-del-desarrollo)
- [Roadmap](#-roadmap)
- [Contribución](#-contribución)
- [Licencia](#-licencia)
- [Contacto](#-contacto)

---

## 📖 Descripción

**BiblioTech** es una aplicación web completa para la gestión integral de bibliotecas. Permite administrar el catálogo de libros, gestionar usuarios con diferentes roles, controlar préstamos y devoluciones mediante una interfaz web moderna, reactiva e intuitiva.

El sistema implementa autenticación con sesiones de Laravel, sistema de roles granular (Admin, Bibliotecario, Miembro), middleware personalizado para control de acceso, y Livewire para componentes reactivos sin necesidad de JavaScript adicional.

### Contexto del Proyecto

Este proyecto nació como una API RESTful de aprendizaje en Laravel y ha evolucionado hacia una aplicación web full-stack completa, aplicando las mejores prácticas y patrones modernos de desarrollo Laravel.

---

## ✨ Características

### Módulos Implementados ✅

- **🔐 Autenticación y Autorización**
  - Registro e inicio de sesión con Laravel Fortify
  - Sistema de roles: Admin, Librarian, Member
  - Middleware personalizado de control de acceso
  - Protección CSRF en formularios

- **📚 Gestión de Libros**
  - CRUD completo con validación robusta
  - Soft deletes y restauración de libros eliminados
  - Actualización de estado (disponible/no disponible)
  - Gestión de copias disponibles
  - Paginación de 15 registros por página
  - Relación con categorías

- **👥 Gestión de Usuarios**
  - Perfiles de usuario editables
  - Soft deletes para auditoría
  - Métodos helper para verificación de roles
  - Historial de préstamos por usuario

- **📖 Gestión de Préstamos**
  - Registro de préstamos con validaciones
  - Verificación de disponibilidad automática
  - Cálculo de fecha de devolución (14 días)
  - Devolución con actualización de inventario
  - Renovación de préstamos (extensión de 7 días)
  - Manejo inteligente de libros eliminados

- **📊 Dashboards por Rol**
  - Dashboard general con redirección automática
  - Dashboard de bibliotecario con estadísticas
  - Dashboards específicos para cada rol

- **🎨 Interfaz de Usuario**
  - Diseño responsive con Tailwind CSS
  - Componentes Flux UI
  - Vistas Blade organizadas por módulo
  - Componentes Livewire reactivos

### Características Técnicas

- ✅ Paginación en todos los listados
- ✅ Validación de datos con reglas de Laravel
- ✅ Eager loading para optimización de consultas
- ✅ Soft deletes en modelos críticos
- ✅ Mensajes flash de éxito/error
- ✅ Manejo estructurado de errores

---

## 🛠 Tecnologías

### Backend
- **Laravel Framework** 12.x
- **PHP** 8.2+
- **MySQL** 8.0

### Frontend
- **Livewire** 3.x
- **Volt** 1.7+
- **Flux UI** 2.9+
- **Tailwind CSS**
- **Alpine.js** (incluido con Livewire)

### Autenticación & Seguridad
- **Laravel Fortify** 1.30+
- **Laravel Sanctum** 4.2+
- Sesiones de Laravel
- Middleware personalizado

### Herramientas de Desarrollo
- **Laravel Pint** (Code Style)
- **Laravel Sail** (Docker environment)
- **Laravel Pail** (Log viewer)
- **PHPUnit** (Testing)

---

## 📦 Requisitos Previos

Antes de instalar, asegúrate de tener:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x y **npm** >= 9.x
- **MySQL** >= 8.0 o **MariaDB** >= 10.3
- **Git**

### Extensiones PHP Requeridas

```bash
php -m | grep -E 'pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath'
```

---

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/guillencristofer911-star/BiblioTech.git
cd BiblioTech
```

### 2. Cambiar a la Rama de Desarrollo

```bash
git checkout dev
```

### 3. Instalar Dependencias

```bash
# Dependencias de PHP
composer install

# Dependencias de Node.js
npm install
```

### 4. Configurar Variables de Entorno

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configurar Base de Datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bibliotech
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar Migraciones

```bash
php artisan migrate
```

### 7. (Opcional) Ejecutar Seeders

```bash
# Cuando estén disponibles
php artisan db:seed
```

### 8. Compilar Assets

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 9. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: [http://localhost:8000](http://localhost:8000)

---

## ⚙️ Configuración

### Configuración de Sesiones

El proyecto utiliza sesiones de Laravel. Asegúrate de configurar correctamente:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Configuración de Livewire

En `config/livewire.php` puedes personalizar:

- Layout de la aplicación
- Ruta de assets
- Middleware aplicado

---

## 💻 Uso

### Acceso al Sistema

1. Visita `http://localhost:8000`
2. Regístrate como nuevo usuario
3. Un administrador debe asignar tu rol

### Roles Disponibles

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Admin** | Administrador del sistema | Acceso total: gestión de usuarios, configuración, reportes |
| **Librarian** | Bibliotecario | Gestión de libros y préstamos |
| **Member** | Usuario regular | Ver catálogo, solicitar préstamos |

### Funcionalidades Principales

#### Para Bibliotecarios y Administradores

**Gestión de Libros:**
```
/books          - Listar todos los libros
/books/create   - Crear nuevo libro
/books/{id}     - Ver detalle de libro
/books/{id}/edit - Editar libro
```

**Gestión de Préstamos:**
```
/loans          - Listar todos los préstamos
/loans/create   - Crear nuevo préstamo
/loans/{id}     - Ver detalle de préstamo
```

**Acciones Especiales:**
- Restaurar libros eliminados
- Actualizar estado de disponibilidad
- Renovar préstamos (7 días adicionales)
- Registrar devoluciones

#### Para Miembros

```
/member/dashboard      - Dashboard personal
/member/loans          - Mis préstamos activos
/member/reservations   - Mis reservaciones (en desarrollo)
```

---

## 👥 Roles y Permisos

### 🔓 Usuario Invitado (Guest)
- Ver página principal
- Registrarse en el sistema

### 👤 Miembro (Member)
- Dashboard personalizado
- Ver mis préstamos
- Actualizar perfil
- Solicitar reservaciones

### 📚 Bibliotecario (Librarian)
- Todo lo de Member +
- Dashboard con estadísticas
- CRUD completo de libros
- CRUD completo de préstamos
- Actualizar estado de libros
- Renovar préstamos
- Registrar devoluciones
- Restaurar libros eliminados

### 👑 Administrador (Admin)
- Todo lo de Librarian +
- Gestión de usuarios
- Configuración del sistema
- Reportes avanzados
- Acceso a todas las funciones

---

## 📂 Estructura del Proyecto

```
BiblioTech/
├── app/
│   ├── Actions/                 # Acciones personalizadas
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Controladores de autenticación
│   │   │   ├── BookController.php
│   │   │   ├── LoanController.php
│   │   │   ├── DashboardController.php
│   │   │   └── LibrarianDashboardController.php
│   │   └── Middleware/
│   │       └── CheckRole.php   # Middleware de roles
│   ├── Livewire/               # Componentes Livewire
│   ├── Models/
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── Loan.php
│   │   └── Category.php
│   └── Providers/
├── database/
│   ├── migrations/             # Migraciones de BD
│   ├── seeders/                # Seeders (en desarrollo)
│   └── factories/              # Factories para testing
├── resources/
│   ├── views/
│   │   ├── books/             # Vistas de libros
│   │   ├── loans/             # Vistas de préstamos
│   │   ├── librarian/         # Vistas de bibliotecario
│   │   ├── layouts/           # Layouts principales
│   │   └── components/        # Componentes Blade
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                # Rutas web
│   └── console.php            # Comandos Artisan
├── tests/                     # Tests automatizados
├── .env.example               # Variables de entorno ejemplo
├── composer.json              # Dependencias PHP
├── package.json               # Dependencias Node
└── README.md                  # Este archivo
```

---

## 🏗 Estado del Desarrollo

### ✅ Completado

- [x] Sistema de autenticación con Fortify
- [x] Sistema de roles y permisos
- [x] CRUD completo de libros
- [x] CRUD completo de préstamos
- [x] Soft deletes en Books y Users
- [x] Dashboard de bibliotecario
- [x] Middleware de control de acceso
- [x] Renovación de préstamos
- [x] Restauración de libros eliminados
- [x] Manejo de libros eliminados en préstamos
- [x] Paginación en listados
- [x] Interfaz responsive con Tailwind
- [x] Integración con Livewire y Flux UI

### 🚧 En Desarrollo

- [ ] Dashboard de administrador (ruta definida)
- [ ] Dashboard de miembro (ruta definida)
- [ ] Sistema de búsqueda avanzada de libros
- [ ] Filtros en listados

### ⏳ Pendiente

- [ ] CRUD completo de categorías
- [ ] Sistema de reservaciones
- [ ] Seeders con datos de prueba
- [ ] Sistema de reportes avanzados
- [ ] Gestión de usuarios (panel admin)
- [ ] Sistema de multas
- [ ] Notificaciones por email
- [ ] Tests automatizados (PHPUnit)
- [ ] API RESTful (opcional)
- [ ] Exportación de reportes (PDF/Excel)

---

## 🗺 Roadmap

### Versión 1.1 (Próximo Release)
- [ ] Completar dashboards de admin y member
- [ ] Implementar CRUD de categorías
- [ ] Agregar búsqueda y filtros avanzados
- [ ] Crear seeders completos
- [ ] Documentación de API (si se implementa)

### Versión 1.2
- [ ] Sistema de reservaciones funcional
- [ ] Gestión de usuarios desde panel admin
- [ ] Sistema de reportes y estadísticas
- [ ] Notificaciones básicas

### Versión 2.0
- [ ] Sistema de multas automático
- [ ] Notificaciones por email
- [ ] API RESTful completa
- [ ] Exportación de reportes
- [ ] Tests automatizados con >80% cobertura

---

## 🤝 Contribución

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'feat: agrega característica increíble'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Convenciones de Commits

Seguimos [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: nueva característica
fix: corrección de bug
docs: cambios en documentación
style: cambios de formato (sin afectar código)
refactor: refactorización de código
test: agregar o modificar tests
chore: cambios en build o herramientas
```

### Estándares de Código

- Seguir PSR-12 para código PHP
- Usar Laravel Pint: `./vendor/bin/pint`
- Comentar código complejo
- Escribir tests para nuevas features

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 📧 Contacto

**Desarrollador:** Guillen Cristofer  
**GitHub:** [@guillencristofer911-star](https://github.com/guillencristofer911-star)  
**Repositorio:** [BiblioTech](https://github.com/guillencristofer911-star/BiblioTech)

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP
- [Livewire](https://livewire.laravel.com) - Componentes reactivos
- [Flux UI](https://flux.laravel.com) - Componentes de interfaz
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- Comunidad de Laravel por recursos y documentación

---

## 📚 Recursos Adicionales

- [Documentación de Laravel 12](https://laravel.com/docs/12.x)
- [Documentación de Livewire 3](https://livewire.laravel.com/docs/3.x)
- [Documentación de Flux](https://flux.laravel.com/docs)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)

---

<div align="center">
  <p>Hecho con ❤️ y Laravel</p>
  <p>⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub</p>
</div>
