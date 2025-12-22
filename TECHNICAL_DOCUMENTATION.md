# 📑 Documentación Técnica - BiblioTech

> **Versión:** 1.0  
> **Última actualización:** Diciembre 2024  
> **Framework:** Laravel 12.x  
> **Autor:** Guillen Cristofer

---

## 📚 Tabla de Contenidos

1. [Arquitectura del Sistema](#1-arquitectura-del-sistema)
2. [Modelos de Datos](#2-modelos-de-datos)
3. [Base de Datos](#3-base-de-datos)
4. [Controladores](#4-controladores)
5. [Rutas y Endpoints](#5-rutas-y-endpoints)
6. [Middleware](#6-middleware)
7. [Vistas y Frontend](#7-vistas-y-frontend)
8. [Diagramas de Flujo](#8-diagramas-de-flujo)
9. [Variables de Entorno](#9-variables-de-entorno)
10. [Convenciones y Estándares](#10-convenciones-y-estándares)

---

## 1. Arquitectura del Sistema

### 1.1 Patrón Arquitectónico

BiblioTech implementa el patrón **MVC (Model-View-Controller)** mejorado con:

- **Modelos Eloquent ORM** para abstracción de base de datos
- **Controladores RESTful** para lógica de negocio
- **Vistas Blade con Livewire** para UI reactiva
- **Middleware personalizado** para autorización

### 1.2 Flujo de Petición HTTP

```
Cliente (Navegador)
       ↓
   Ruta (web.php)
       ↓
   Middleware (auth, check.role)
       ↓
   Controlador
       ↓
   Modelo (Eloquent)
       ↓
   Base de Datos (MySQL)
       ↓
   Vista (Blade + Livewire)
       ↓
   Respuesta al Cliente
```

### 1.3 Diagrama de Capas

```
┌──────────────────────────────────────────────┐
│          CAPA DE PRESENTACIÓN                   │
│   (Blade Templates + Livewire + Flux UI)       │
└──────────────────────────────────────────────┘
                    ↓
┌──────────────────────────────────────────────┐
│          CAPA DE LÓGICA DE NEGOCIO              │
│    (Controllers + Middleware + Policies)      │
└──────────────────────────────────────────────┘
                    ↓
┌──────────────────────────────────────────────┐
│          CAPA DE ACCESO A DATOS                 │
│         (Eloquent Models + Migrations)         │
└──────────────────────────────────────────────┘
                    ↓
┌──────────────────────────────────────────────┐
│              BASE DE DATOS                      │
│                 (MySQL 8.0)                    │
└──────────────────────────────────────────────┘
```

---

## 2. Modelos de Datos

### 2.1 Modelo: User

**Ubicación:** `app/Models/User.php`

#### Propósito
Representa a los usuarios del sistema con diferentes roles de acceso.

#### Atributos Fillable

```php
protected $fillable = [
    'name',         // string - Nombre completo del usuario
    'email',        // string - Email único (login)
    'password',     // string - Contraseña hasheada
    'phone',        // string - Teléfono de contacto
    'role',         // enum - admin|librarian|member
];
```

#### Atributos Hidden

```php
protected $hidden = [
    'password',       // Oculto en JSON
    'remember_token', // Oculto en JSON
];
```

#### Casts

```php
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];
```

#### Traits

- `HasFactory` - Permite usar factories para testing
- `SoftDeletes` - Eliminación suave (campo `deleted_at`)

#### Relaciones

```php
// Relación uno a muchos con Loan
public function loans()
{
    return $this->hasMany(Loan::class, 'user_id');
}

// Relación uno a muchos con Loan (solo activos)
public function activeLoans()
{
    return $this->hasMany(Loan::class, 'user_id')
                ->whereNull('returned_at');
}
```

#### Métodos Helper

| Método | Retorno | Descripción |
|--------|---------|-------------|
| `initials()` | string | Iniciales del nombre (máx 2) |
| `isAdmin()` | bool | Verifica si es administrador |
| `isLibrarian()` | bool | Verifica si es bibliotecario |
| `isMember()` | bool | Verifica si es miembro |
| `isStaff()` | bool | Verifica si es admin o librarian |
| `isActive()` | bool | Verifica si la cuenta está activa |

---

### 2.2 Modelo: Book

**Ubicación:** `app/Models/Book.php`

#### Propósito
Representa el catálogo de libros de la biblioteca.

#### Atributos Fillable

```php
protected $fillable = [
    'title',              // string - Título del libro
    'author',             // string - Autor del libro
    'isbn',               // string - ISBN único
    'publication_year',   // year - Año de publicación
    'category_id',        // foreignId - FK a categories
    'copies_available',   // integer - Copias disponibles
    'status',             // enum - available|unavailable
];
```

#### Casts

```php
protected $casts = [
    'publication_year' => 'date:Y', // Cast a año (YYYY)
];
```

#### Traits

- `SoftDeletes` - Eliminación suave para auditoría

#### Relaciones

```php
// Relación muchos a uno con Category
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

// Relación uno a muchos con Loan (implícita)
public function loans()
{
    return $this->hasMany(Loan::class, 'book_id');
}
```

#### Validaciones Implementadas

- `title`: required, string, max:255
- `author`: required, string, max:255
- `isbn`: required, string, max:13, unique
- `publication_year`: required, integer, min:1000, max:año actual
- `category_id`: nullable, exists:categories,id
- `copies_available`: required, integer, min:0
- `status`: required, in:available,unavailable

---

### 2.3 Modelo: Loan

**Ubicación:** `app/Models/Loan.php`

#### Propósito
Registra los préstamos de libros a usuarios.

#### Atributos Fillable

```php
protected $fillable = [
    'user_id',       // foreignId - FK a users
    'book_id',       // foreignId - FK a books
    'loan_date',     // date - Fecha de préstamo
    'due_date',      // date - Fecha de devolución esperada
    'return_date',   // date - Fecha de devolución real (nullable)
    'status',        // enum - ongoing|returned|overdue
];
```

#### Casts

```php
protected $casts = [
    'loan_date' => 'datetime',
    'due_date' => 'datetime',
    'return_date' => 'datetime',
];
```

#### Relaciones

```php
// Relación muchos a uno con Book
public function book()
{
    return $this->belongsTo(Book::class);
}

// Relación muchos a uno con User
public function user()
{
    return $this->belongsTo(User::class);
}
```

#### Lógica de Negocio

- **Duración de préstamo:** 14 días automáticos
- **Renovación:** +7 días adicionales
- **Estados posibles:**
  - `ongoing` - Préstamo activo
  - `returned` - Libro devuelto
  - `overdue` - Préstamo vencido

---

### 2.4 Modelo: Category

**Ubicación:** `app/Models/Category.php`

#### Propósito
Categoriza los libros del catálogo.

#### Atributos Fillable

```php
protected $fillable = [
    'name',         // string - Nombre de la categoría
    'description',  // text - Descripción (nullable)
];
```

#### Relaciones

```php
// Relación uno a muchos con Book
public function books()
{
    return $this->hasMany(Book::class, 'category_id');
}
```

---

## 3. Base de Datos

### 3.1 Diagrama de Relaciones (ERD)

```
┌─────────────────────────────────────────┐
│                  USERS                         │
│─────────────────────────────────────────│
│ PK  id (bigint)                               │
│     name (varchar)                            │
│     email (varchar) UNIQUE                    │
│     password (varchar)                        │
│     phone (varchar)                           │
│     role (enum: admin|librarian|member)       │
│     email_verified_at (timestamp)             │
│     remember_token (varchar)                  │
│     deleted_at (timestamp) NULL               │
│     created_at (timestamp)                    │
│     updated_at (timestamp)                    │
└─────────────────────────────────────────┘
           │
           │ 1
           │
           │
           │ N
           ↓
┌─────────────────────────────────────────┐
│                  LOANS                         │
│─────────────────────────────────────────│
│ PK  id (bigint)                               │
│ FK  user_id (bigint) → users.id             │
│ FK  book_id (bigint) → books.id             │
│     loan_date (date)                          │
│     due_date (date)                           │
│     return_date (date) NULL                   │
│     status (enum: ongoing|returned|overdue)   │
│     created_at (timestamp)                    │
│     updated_at (timestamp)                    │
└─────────────────────────────────────────┘
           │
           │ N
           │
           │
           │ 1
           ↓
┌─────────────────────────────────────────┐
│                  BOOKS                         │
│─────────────────────────────────────────│
│ PK  id (bigint)                               │
│     title (varchar)                           │
│     author (varchar)                          │
│     isbn (varchar) UNIQUE                     │
│     publication_year (year)                   │
│ FK  category_id (bigint) → categories.id    │
│     copies_available (integer)                │
│     status (enum: available|unavailable)      │
│     deleted_at (timestamp) NULL               │
│     created_at (timestamp)                    │
│     updated_at (timestamp)                    │
└─────────────────────────────────────────┘
           │
           │ N
           │
           │
           │ 1
           ↓
┌─────────────────────────────────────────┐
│               CATEGORIES                      │
│─────────────────────────────────────────│
│ PK  id (bigint)                               │
│     name (varchar)                            │
│     description (text) NULL                   │
│     created_at (timestamp)                    │
│     updated_at (timestamp)                    │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│               SESSIONS                        │
│─────────────────────────────────────────│
│ PK  id (varchar)                              │
│ FK  user_id (bigint) NULL → users.id       │
│     ip_address (varchar) NULL                 │
│     user_agent (text)                         │
│     payload (longtext)                        │
│     last_activity (integer)                   │
└─────────────────────────────────────────┘
```

### 3.2 Migraciones

#### Tabla: users

**Archivo:** `database/migrations/0001_01_01_000000_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->enum('role', ['admin', 'librarian', 'member'])->default('member');
    $table->rememberToken();
    $table->softDeletes();
    $table->timestamps();
});
```

#### Tabla: categories

**Archivo:** `database/migrations/2025_12_16_131451_create_categories_table.php`

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

#### Tabla: books

**Archivo:** `database/migrations/2025_12_16_131452_create_books_table.php`

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('author');
    $table->string('isbn')->unique();
    $table->year('publication_year');
    $table->foreignId('category_id')
          ->nullable()
          ->constrained('categories')  
          ->onDelete('restrict');
    $table->integer('copies_available')->default(0);
    $table->enum('status', ['available', 'unavailable'])->default('available');
    $table->softDeletes();
    $table->timestamps();
});
```

#### Tabla: loans

**Archivo:** `database/migrations/2025_12_16_131600_create_loans_table.php`

```php
Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
        ->constrained('users')
        ->onDelete('restrict');
    $table->foreignId('book_id')
        ->constrained('books')
        ->onDelete('restrict');
    $table->date('loan_date');
    $table->date('due_date');
    $table->date('return_date')->nullable();
    $table->enum('status', ['ongoing', 'returned', 'overdue'])->default('ongoing');
    $table->timestamps();
});
```

#### Tabla: sessions

**Archivo:** `database/migrations/2025_12_21_194239_create_sessions_table.php`

```php
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

### 3.3 Índices y Restricciones

| Tabla | Campo | Tipo | Restricción |
|-------|-------|------|---------------|
| users | email | UNIQUE | Único por usuario |
| users | id | PRIMARY KEY | Auto-incremental |
| books | isbn | UNIQUE | ISBN único |
| books | id | PRIMARY KEY | Auto-incremental |
| books | category_id | FOREIGN KEY | ON DELETE RESTRICT |
| loans | user_id | FOREIGN KEY | ON DELETE RESTRICT |
| loans | book_id | FOREIGN KEY | ON DELETE RESTRICT |
| sessions | user_id | INDEX | Para búsquedas rápidas |

---

## 4. Controladores

### 4.1 BookController

**Ubicación:** `app/Http/Controllers/BookController.php`  
**Responsabilidad:** Gestionar el CRUD completo de libros

#### Métodos

##### `index()`
**Propósito:** Listar todos los libros con paginación

```php
public function index()
{
    $books = Book::withTrashed('category')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    return view('books.index', compact('books'));
}
```

**Consulta SQL generada:**
```sql
SELECT * FROM books 
ORDER BY created_at DESC 
LIMIT 15 OFFSET 0;
```

**Vista retornada:** `resources/views/books/index.blade.php`

##### `create()`
**Propósito:** Mostrar formulario de creación

```php
public function create()
{
    $categories = Category::all();
    return view('books.create', compact('categories'));
}
```

**Vista retornada:** `resources/views/books/create.blade.php`

##### `store(Request $request)`
**Propósito:** Almacenar un nuevo libro

**Validaciones:**
- `title`: required|string|max:255
- `author`: required|string|max:255
- `isbn`: required|string|max:13|unique:books
- `publication_year`: required|integer|min:1000|max:{año actual}
- `category_id`: nullable|exists:categories,id
- `copies_available`: required|integer|min:0
- `status`: required|in:available,unavailable

**Respuesta exitosa:** Redirige a `books.index` con mensaje de éxito

##### `show($id)`
**Propósito:** Mostrar detalle de un libro

```php
public function show($id)
{
    $book = Book::findOrFail($id);
    return view('books.show', compact('book'));
}
```

**Vista retornada:** `resources/views/books/show.blade.php`

##### `edit($id)`
**Propósito:** Mostrar formulario de edición

```php
public function edit(string $id)
{
    $book = Book::findOrFail($id);
    $categories = Category::all();
    return view('books.edit', compact('book', 'categories')); 
}
```

**Vista retornada:** `resources/views/books/edit.blade.php`

##### `update(Request $request, $id)`
**Propósito:** Actualizar datos de un libro

**Validaciones:** Mismas que `store()` excepto:
- `isbn`: `unique:books,isbn,{$id}` (excluye el registro actual)

**Respuesta exitosa:** Redirige a `books.index` con mensaje de éxito

##### `destroy($id)`
**Propósito:** Eliminar libro (soft delete)

```php
public function destroy($id)
{
    $book = Book::findOrFail($id);
    $book->delete(); // Soft delete
    return redirect()->route('books.index')
        ->with('success', 'Libro eliminado exitosamente.');
}
```

**Comportamiento:** Marca `deleted_at` con timestamp actual

##### `restore($id)` ✨
**Propósito:** Restaurar libro eliminado

```php
public function restore($id)
{
    $book = Book::withTrashed()->findOrFail($id);
    $book->restore();
    
    return redirect()->route('books.index')
        ->with('success', 'Libro restaurado exitosamente.');
}
```

##### `updateStatus(Request $request, $id)` ✨
**Propósito:** Actualizar solo el estado del libro

**Validaciones:**
- `status`: required|in:available,unavailable

**Respuesta exitosa:** Redirige a `books.index` con mensaje de éxito

---

### 4.2 LoanController

**Ubicación:** `app/Http/Controllers/LoanController.php`  
**Responsabilidad:** Gestionar préstamos de libros

#### Métodos

##### `index()`
**Propósito:** Listar préstamos con relaciones

```php
public function index()
{
    $loans = Loan::with(['user', 'book' => function ($query) {
        $query->withTrashed();
    }])
    ->orderBy('created_at', 'desc')
    ->paginate(15);
    
    return view('loans.index', compact('loans'));
}
```

**Característica especial:** Carga libros eliminados con `withTrashed()`

##### `create()`
**Propósito:** Mostrar formulario de nuevo préstamo

```php
public function create()
{
    $users = User::all();
    $books = Book::where('copies_available', '>', 0)->get();
    return view('loans.create', compact('users', 'books'));
}
```

**Lógica:** Solo muestra libros con copias disponibles

##### `store(Request $request)`
**Propósito:** Crear nuevo préstamo

**Validaciones:**
- `user_id`: required|exists:users,id
- `book_id`: required|exists:books,id

**Lógica de negocio:**
```php
$book = Book::findOrFail($validatedData['book_id']);

if ($book->copies_available <= 0) {
    return redirect()->back()
        ->withErrors(['book_id' => 'No hay copias disponibles']);
}

Loan::create([
    'user_id' => $validatedData['user_id'],
    'book_id' => $validatedData['book_id'],
    'loan_date' => now(),
    'due_date' => now()->addDays(14), // 14 días
    'status' => 'ongoing',
]);

$book->decrement('copies_available'); // Reduce inventario
```

##### `show($id)`
**Propósito:** Mostrar detalle de préstamo

```php
public function show($id)
{
    $loan = Loan::with(['user', 'book' => function ($query) {
        $query->withTrashed();
    }])->findOrFail($id);
    
    return view('loans.show', compact('loan'));
}
```

##### `return($id)` ✨
**Propósito:** Registrar devolución de libro

```php
public function return($id)
{
    $loan = Loan::with(['book' => function ($q) {
        $q->withTrashed(); 
    }])->findOrFail($id);

    $loan->status = 'returned';
    $loan->return_date = now();
    $loan->save();

    if ($loan->book) {
        $loan->book->increment('copies_available'); // Incrementa inventario
    }

    return redirect()->route('loans.index')
        ->with('success', 'Libro devuelto exitosamente.');
}
```

##### `renew($id)` ✨
**Propósito:** Renovar préstamo (extensión de 7 días)

```php
public function renew($id)
{
    $loan = Loan::with(['book' => function ($query) {
        $query->withTrashed();
    }])->findOrFail($id);
    
    if (!$loan->book) {
        return redirect()->route('loans.index')
            ->with('error', 'No se puede renovar: el libro ha sido eliminado.');
    }
    
    $loan->due_date = $loan->due_date->addDays(7); // +7 días
    $loan->save();

    return redirect()->route('loans.index')
        ->with('success', 'Préstamo renovado exitosamente.');
}
```

---

### 4.3 DashboardController

**Ubicación:** `app/Http/Controllers/DashboardController.php`  
**Responsabilidad:** Redirigir a dashboard según rol

#### Método: `index()`

```php
public function index()
{
    $user = Auth::user();
   
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'librarian':
            return redirect()->route('librarian.dashboard');
        case 'member':
            return redirect()->route('member.dashboard');
        default:
            return redirect()->route('unauthorized');
    }
}
```

**Flujo:**
```
Usuario autenticado
       ↓
   ¿Cuál es su rol?
       ↓
   ┌───────────────┐
   │   Admin?       │ → /admin/dashboard
   ├───────────────┤
   │   Librarian?   │ → /librarian/dashboard
   ├───────────────┤
   │   Member?      │ → /member/dashboard
   ├───────────────┤
   │   Otro?        │ → /unauthorized
   └───────────────┘
```

---

### 4.4 LibrarianDashboardController

**Ubicación:** `app/Http/Controllers/LibrarianDashboardController.php`  
**Responsabilidad:** Mostrar estadísticas del bibliotecario

#### Método: `index()`

```php
public function index()
{
    // Estadísticas de préstamos
    $totalLoans = Loan::count();
    $ongoingLoans = Loan::where('status', 'ongoing')->count();
    $overdueLoans = Loan::where('status', 'overdue')->count();
    
    // Estadísticas de libros
    $totalBooks = Book::count();
    $availableBooks = Book::where('status', 'available')->count();
    $unavailableBooks = Book::where('status', 'unavailable')->count();
    
    // Traducciones para vistas
    $rolTranslation = [
        'librarian' => 'Bibliotecario',
        'admin' => 'Administrador',
        'member' => 'Miembro',
    ];
    
    $statusTranslation = [
        'ongoing' => 'En curso',
        'returned' => 'Devuelto',
        'overdue' => 'Atrasado',
        'unavailable' => 'No disponible',
        'available' => 'Disponible',
    ];
    
    return view('librarian.dashboard', [
        'totalBooks' => $totalBooks,
        'availableBooks' => $availableBooks,
        'unavailableBooks' => $unavailableBooks,
        'totalLoans' => $totalLoans,
        'ongoingLoans' => $ongoingLoans,
        'overdueLoans' => $overdueLoans,
        'rolTranslation' => $rolTranslation,
        'statusTranslation' => $statusTranslation,
    ]);
}
```

**Consultas SQL generadas:**
```sql
-- Total de libros
SELECT COUNT(*) FROM books;

-- Libros disponibles
SELECT COUNT(*) FROM books WHERE status = 'available';

-- Préstamos activos
SELECT COUNT(*) FROM loans WHERE status = 'ongoing';

-- Préstamos atrasados
SELECT COUNT(*) FROM loans WHERE status = 'overdue';
```

---

## 5. Rutas y Endpoints

### 5.1 Rutas Públicas

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/` | home | - | Página de bienvenida |
| GET | `/unauthorized` | unauthorized | - | Página de acceso denegado |

### 5.2 Rutas Autenticadas

**Middleware:** `auth`

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/dashboard` | dashboard | DashboardController@index | Redirige según rol |
| GET | `/profile` | profile | - | Perfil de usuario |

### 5.3 Rutas de Librarian y Admin

**Middleware:** `auth`, `check.role:librarian,admin`

#### Dashboard

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/librarian/dashboard` | librarian.dashboard | LibrarianDashboardController@index | Dashboard de bibliotecario |

#### Libros (Resource)

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/books` | books.index | BookController@index | Listar libros |
| GET | `/books/create` | books.create | BookController@create | Formulario crear libro |
| POST | `/books` | books.store | BookController@store | Guardar nuevo libro |
| GET | `/books/{id}` | books.show | BookController@show | Ver detalle libro |
| GET | `/books/{id}/edit` | books.edit | BookController@edit | Formulario editar libro |
| PUT/PATCH | `/books/{id}` | books.update | BookController@update | Actualizar libro |
| DELETE | `/books/{id}` | books.destroy | BookController@destroy | Eliminar libro (soft) |

#### Libros (Rutas Personalizadas)

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| PATCH | `/books/{id}/status` | books.updateStatus | BookController@updateStatus | Actualizar estado |
| PATCH | `/books/{id}/copies` | books.updateCopies | BookController@updateCopies | Actualizar copias |
| PATCH | `/books/{id}/restore` | books.restore | BookController@restore | Restaurar libro |

#### Préstamos (Resource)

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/loans` | loans.index | LoanController@index | Listar préstamos |
| GET | `/loans/create` | loans.create | LoanController@create | Formulario crear préstamo |
| POST | `/loans` | loans.store | LoanController@store | Guardar nuevo préstamo |
| GET | `/loans/{id}` | loans.show | LoanController@show | Ver detalle préstamo |

#### Préstamos (Rutas Personalizadas)

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| PATCH | `/loans/{id}/return` | loans.return | LoanController@return | Registrar devolución |
| PATCH | `/loans/{id}/renew` | loans.renew | LoanController@renew | Renovar préstamo |

### 5.4 Rutas Solo Admin

**Middleware:** `auth`, `check.role:admin`

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/admin/dashboard` | admin.dashboard | - | Dashboard administrador |
| GET | `/admin/users` | admin.users | - | Gestión usuarios |
| GET | `/admin/settings` | admin.settings | - | Configuración sistema |
| GET | `/admin/reports` | admin.reports | - | Reportes avanzados |

### 5.5 Rutas Solo Member

**Middleware:** `auth`, `check.role:member`

| Método | Ruta | Nombre | Controlador | Descripción |
|--------|------|--------|-------------|-------------|
| GET | `/member/dashboard` | member.dashboard | - | Dashboard miembro |
| GET | `/member/loans` | member.loans | - | Mis préstamos |
| GET | `/member/reservations` | member.reservations | - | Mis reservaciones |

---

## 6. Middleware

### 6.1 CheckRole Middleware

**Ubicación:** `app/Http/Middleware/CheckRole.php`  
**Propósito:** Verificar que el usuario tenga uno de los roles permitidos

#### Código Completo

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()->route('unauthorized');
        }

        // Permitir acceso
        return $next($request);
    }
}
```

#### Uso en Rutas

```php
// Un solo rol
Route::middleware(['auth', 'check.role:admin'])->group(function () {
    // Solo admin puede acceder
});

// Múltiples roles
Route::middleware(['auth', 'check.role:librarian,admin'])->group(function () {
    // Librarian y Admin pueden acceder
});
```

#### Diagrama de Flujo

```
       Petición HTTP
              ↓
     ¿Usuario autenticado?
       /              \
     NO               SÍ
      ↓                ↓
 redirect(login)  ¿Rol permitido?
                   /           \
                 NO             SÍ
                  ↓              ↓
        redirect(unauthorized)  Continuar
```

### 6.2 Middleware Global

Configurados en `bootstrap/app.php`:

- `auth` - Verifica autenticación (Laravel Fortify)
- `guest` - Solo usuarios no autenticados
- `verified` - Email verificado
- `throttle` - Rate limiting

---

## 7. Vistas y Frontend

### 7.1 Estructura de Vistas

```
resources/views/
├── layouts/
│   ├── app.blade.php         # Layout principal
│   └── guest.blade.php       # Layout para guests
├── components/
│   ├── navbar.blade.php      # Barra de navegación
│   ├── sidebar.blade.php     # Menú lateral
│   └── alert.blade.php       # Mensajes flash
├── books/
│   ├── index.blade.php       # Listado de libros
│   ├── create.blade.php      # Formulario crear
│   ├── edit.blade.php        # Formulario editar
│   └── show.blade.php        # Detalle libro
├── loans/
│   ├── index.blade.php       # Listado préstamos
│   ├── create.blade.php      # Formulario crear
│   └── show.blade.php        # Detalle préstamo
├── librarian/
│   └── dashboard.blade.php   # Dashboard bibliotecario
├── Auth/                     # Vistas autenticación (Fortify)
├── dashboard.blade.php       # Dashboard general
├── welcome.blade.php         # Página inicio
└── unauthorized.blade.php    # Acceso denegado
```

### 7.2 Componentes Flux UI

Flux proporciona componentes pre-construidos:

```blade
{{-- Botones --}}
<flux:button variant="primary">Guardar</flux:button>

{{-- Inputs --}}
<flux:input name="title" label="Título" />

{{-- Tablas --}}
<flux:table>
    <flux:thead>
        <flux:tr>
            <flux:th>ID</flux:th>
            <flux:th>Título</flux:th>
        </flux:tr>
    </flux:thead>
</flux:table>

{{-- Tarjetas --}}
<flux:card>
    <flux:heading>Título</flux:heading>
    <flux:text>Contenido</flux:text>
</flux:card>
```

### 7.3 Livewire Components

**Ubicación:** `app/Livewire/`

Livewire permite componentes reactivos sin JavaScript:

```php
// Ejemplo de componente Livewire
class BookSearch extends Component
{
    public $search = '';
    
    public function render()
    {
        return view('livewire.book-search', [
            'books' => Book::where('title', 'like', '%'.$this->search.'%')->get()
        ]);
    }
}
```

```blade
{{-- Uso en vista --}}
<livewire:book-search />
```

---

## 8. Diagramas de Flujo

### 8.1 Flujo de Autenticación
```
                  Usuario visita /login
                          ↓
                  Ingresa credenciales
                          ↓
               Laravel Fortify valida
                    /           \
              Válido         Inválido
                ↓                ↓
        Crea sesión    Muestra error
                ↓                ↓
        Redirige a        Vuelve a
        /dashboard        /login
                ↓
        DashboardController
        verifica rol
                ↓
        ┌───────────────┐
        │   Admin?       │ → /admin/dashboard
        ├───────────────┤
        │   Librarian?   │ → /librarian/dashboard
        ├───────────────┤
        │   Member?      │ → /member/dashboard
        └───────────────┘
```

### 8.2 Flujo de Creación de Préstamo

```
    Usuario (Librarian/Admin) visita /loans/create
                    ↓
            Selecciona usuario
                    ↓
           Selecciona libro disponible
                    ↓
             Envía formulario
                    ↓
         LoanController@store recibe datos
                    ↓
              Valida datos
                /           \
          Válido         Inválido
             ↓                ↓
    ¿Hay copias        Muestra error
     disponibles?       y vuelve a form
       /      \
     SÍ        NO
      ↓         ↓
  Crea loan   Error:
  loan_date = now()   "No hay copias"
  due_date = now()+14d
  status = ongoing
      ↓
  Decrementa
  copies_available
  del libro
      ↓
  Redirige a /loans
  con mensaje éxito
```

### 8.3 Flujo de Devolución de Libro

```
    Usuario hace clic en "Devolver" en /loans
                    ↓
          PATCH /loans/{id}/return
                    ↓
        LoanController@return recibe ID
                    ↓
        Busca préstamo con ID
        (incluye libro con withTrashed)
                    ↓
             ¿Préstamo existe?
               /          \
             SÍ            NO
              ↓             ↓
     Actualiza loan   Error 404
     status = returned
     return_date = now()
              ↓
      ¿Libro existe?
        /         \
      SÍ          NO (eliminado)
       ↓              ↓
  Incrementa    No actualiza
  copies_available  inventario
       ↓              ↓
       └──────────────┘
              ↓
     Redirige a /loans
     con mensaje éxito
```

### 8.4 Flujo de Middleware CheckRole

```
       Petición a ruta protegida
       (ej: /books/create)
                ↓
        Middleware: auth
                ↓
       ¿Usuario autenticado?
         /            \
       SÍ              NO
        ↓               ↓
   Middleware:     Redirige a
   check.role      /login
        ↓
   ¿Rol en lista permitida?
   (librarian, admin)
      /              \
    SÍ                NO
     ↓                 ↓
  Permite acceso   Redirige a
  al controlador   /unauthorized
     ↓
  BookController
  @create
     ↓
  Retorna vista
  books/create
```

### 8.5 Flujo de Soft Delete y Restauración
```
           Usuario hace clic "Eliminar libro"
                         ↓
                DELETE /books/{id}
                         ↓
              BookController@destroy
                         ↓
              $book->delete()
              (Soft Delete)
                         ↓
            Establece deleted_at = now()
            (Registro NO se elimina de BD)
                         ↓
       Libro oculto en consultas normales
       (Book::all() no lo muestra)
                         ↓
       Pero se puede acceder con:
       Book::withTrashed()->find($id)
                         ↓
     Usuario hace clic "Restaurar"
                         ↓
            PATCH /books/{id}/restore
                         ↓
            BookController@restore
                         ↓
         $book->restore()
                         ↓
      Establece deleted_at = NULL
                         ↓
      Libro visible nuevamente
```

---

## 9. Variables de Entorno

### 9.1 Configuración de Base de Datos

```env
DB_CONNECTION=mysql         # Tipo de BD (mysql|pgsql|sqlite)
DB_HOST=127.0.0.1          # Host de la BD
DB_PORT=3306               # Puerto (3306 para MySQL)
DB_DATABASE=bibliotech     # Nombre de la BD
DB_USERNAME=root           # Usuario de BD
DB_PASSWORD=password       # Contraseña de BD
```

### 9.2 Configuración de Aplicación

```env
APP_NAME=BiblioTech        # Nombre de la app
APP_ENV=local              # Entorno (local|production)
APP_KEY=base64:...         # Clave de encriptación
APP_DEBUG=true             # Mostrar errores detallados
APP_URL=http://localhost   # URL base
```

### 9.3 Configuración de Sesiones

```env
SESSION_DRIVER=database    # Driver de sesiones (file|cookie|database|redis)
SESSION_LIFETIME=120       # Duración en minutos
SESSION_ENCRYPT=false      # Encriptar sesiones
```

### 9.4 Configuración de Cache

```env
CACHE_DRIVER=file          # Driver de cache (file|redis|memcached)
QUEUE_CONNECTION=sync      # Driver de colas (sync|database|redis)
```

### 9.5 Configuración de Mail (Opcional)

```env
MAIL_MAILER=smtp           # Mailer (smtp|sendmail|mailgun)
MAIL_HOST=smtp.mailtrap.io # Host SMTP
MAIL_PORT=2525             # Puerto SMTP
MAIL_USERNAME=null         # Usuario SMTP
MAIL_PASSWORD=null         # Contraseña SMTP
MAIL_ENCRYPTION=null       # Encriptación (tls|ssl)
MAIL_FROM_ADDRESS=null     # Email remitente
MAIL_FROM_NAME="${APP_NAME}" # Nombre remitente
```

---

## 10. Convenciones y Estándares

### 10.1 Nomenclatura

#### Modelos
- Singular, PascalCase: `User`, `Book`, `Loan`
- Archivos: `User.php`, `Book.php`

#### Controladores
- PascalCase + "Controller": `BookController`, `LoanController`
- Archivos: `BookController.php`

#### Vistas
- Carpetas en plural: `books/`, `loans/`
- Archivos en snake_case: `index.blade.php`, `create.blade.php`

#### Rutas
- Nombres en dot notation: `books.index`, `loans.create`
- URLs en kebab-case: `/books`, `/member-dashboard`

#### Variables
- camelCase: `$totalBooks`, `$availableBooks`

#### Métodos
- camelCase: `isAdmin()`, `activeLoans()`

### 10.2 Estándares PSR

El proyecto sigue **PSR-12** (PHP Standards Recommendations):

- Indentación: 4 espacios
- Líneas: Máx 120 caracteres
- Llaves: Estilo "same line" para clases y métodos
- Use statements: Ordenados alfabéticamente

**Verificar con Laravel Pint:**
```bash
./vendor/bin/pint
```

### 10.3 Commits (Conventional Commits)

Formato:
```
<tipo>: <descripción corta>

<descripción detallada (opcional)>
```

**Tipos:**
- `feat`: Nueva funcionalidad
- `fix`: Corrección de bug
- `docs`: Cambios en documentación
- `style`: Formato, punto y coma faltante, etc.
- `refactor`: Refactorización de código
- `test`: Agregar o modificar tests
- `chore`: Actualización de dependencias, build, etc.

**Ejemplos:**
```bash
git commit -m "feat: agrega renovación de préstamos"
git commit -m "fix: corrige validación de ISBN en libros"
git commit -m "docs: actualiza documentación técnica"
```

### 10.4 Validaciones

**Siempre validar en el controlador:**
```php
$validatedData = $request->validate([
    'title' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
]);
```

**Reglas comunes:**
- `required` - Campo obligatorio
- `nullable` - Campo opcional
- `string` - Debe ser texto
- `integer` - Debe ser entero
- `email` - Formato email válido
- `unique:tabla,columna` - Valor único en BD
- `exists:tabla,columna` - Valor existe en BD
- `min:x` - Mínimo x caracteres/valor
- `max:x` - Máximo x caracteres/valor
- `in:val1,val2` - Debe estar en lista

### 10.5 Seguridad

#### Protección CSRF
```blade
<form method="POST">
    @csrf
    <!-- campos -->
</form>
```

#### Mass Assignment Protection
```php
// En modelos: usar $fillable o $guarded
protected $fillable = ['name', 'email'];
```

#### SQL Injection Prevention
```php
// CORRECTO (usa bindings)
User::where('email', $email)->first();

// INCORRECTO (vulnerable)
DB::select("SELECT * FROM users WHERE email = '$email'");
```

#### XSS Protection
```blade
{{-- Blade escapa automáticamente --}}
{{ $user->name }} {{-- Seguro --}}

{!! $user->name !!} {{-- Inseguro, evitar --}}
```

---

## 11. Optimizaciones Implementadas

### 11.1 Eager Loading

**Problema N+1 resuelto:**
```php
// MAL - Genera N+1 queries
$loans = Loan::all();
foreach ($loans as $loan) {
    echo $loan->user->name; // Query adicional por cada loan
}

// BIEN - Solo 2 queries
$loans = Loan::with(['user', 'book'])->get();
foreach ($loans as $loan) {
    echo $loan->user->name; // Sin queries adicionales
}
```

### 11.2 Paginación

Todos los listados implementan paginación:
```php
$books = Book::paginate(15); // 15 registros por página
```

### 11.3 Índices de Base de Datos

- `users.email` - Índice UNIQUE para login rápido
- `books.isbn` - Índice UNIQUE para búsquedas
- Foreign Keys automáticamente indexadas

### 11.4 Soft Deletes

Permite "deshacer" eliminaciones sin perder datos:
```php
$book->delete();    // Marca deleted_at
$book->restore();   // Limpia deleted_at
```

---

## 12. Testing (Pendiente)

### 12.1 Estructura Recomendada

```
tests/
├── Feature/
│   ├── BookTest.php          # Tests de CRUD de libros
│   ├── LoanTest.php          # Tests de préstamos
│   └── AuthTest.php          # Tests de autenticación
└── Unit/
    ├── UserTest.php          # Tests de métodos User
    └── BookTest.php          # Tests de métodos Book
```

### 12.2 Comandos

```bash
# Ejecutar todos los tests
php artisan test

# Test específico
php artisan test --filter BookTest

# Con cobertura
php artisan test --coverage
```

---

## 13. Comandos Útiles

### 13.1 Artisan

```bash
# Migraciones
php artisan migrate              # Ejecutar migraciones
php artisan migrate:rollback     # Deshacer última migración
php artisan migrate:fresh        # Eliminar todo y migrar desde cero
php artisan migrate:fresh --seed # Migrar y ejecutar seeders

# Cache
php artisan cache:clear          # Limpiar cache
php artisan config:clear         # Limpiar cache de config
php artisan route:clear          # Limpiar cache de rutas
php artisan view:clear           # Limpiar cache de vistas

# Desarrollo
php artisan tinker               # REPL interactivo
php artisan route:list           # Listar todas las rutas
php artisan make:controller Name # Crear controlador
php artisan make:model Name      # Crear modelo
php artisan make:migration name  # Crear migración

# Optimización (producción)
php artisan config:cache         # Cachear configuración
php artisan route:cache          # Cachear rutas
php artisan view:cache           # Cachear vistas
```

### 13.2 Composer

```bash
composer install          # Instalar dependencias
composer update           # Actualizar dependencias
composer dump-autoload    # Regenerar autoload
```

### 13.3 NPM

```bash
npm install               # Instalar dependencias
npm run dev               # Compilar assets (desarrollo)
npm run build             # Compilar assets (producción)
npm run watch             # Compilar y observar cambios
```

---

## 14. Troubleshooting

### 14.1 Problemas Comunes

#### Error: "Class not found"
```bash
composer dump-autoload
```

#### Error: "Mix manifest not found"
```bash
npm install
npm run dev
```

#### Error: "No application encryption key"
```bash
php artisan key:generate
```

#### Error de migraciones
```bash
php artisan migrate:fresh  # CUIDADO: Elimina todos los datos
```

#### Cache causando problemas
```bash
php artisan optimize:clear  # Limpia todo el cache
```

---

## 15. Referencias

### 15.1 Documentación Oficial

- [Laravel 12.x](https://laravel.com/docs/12.x)
- [Livewire 3.x](https://livewire.laravel.com/docs/3.x)
- [Flux UI](https://flux.laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/12.x/eloquent)
- [Blade Templates](https://laravel.com/docs/12.x/blade)

### 15.2 Convenciones

- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)

---

## 16. Glosario

| Término | Definición |
|---------|-------------|
| **Eager Loading** | Cargar relaciones de BD en una sola consulta |
| **Soft Delete** | Marcado lógico de eliminación sin borrar registro |
| **Middleware** | Capa intermedia que filtra peticiones HTTP |
| **Eloquent** | ORM (Object-Relational Mapping) de Laravel |
| **Blade** | Motor de plantillas de Laravel |
| **Migration** | Script de modificación de estructura de BD |
| **Seeder** | Script para poblar BD con datos de prueba |
| **Factory** | Clase para generar datos falsos (testing) |
| **CRUD** | Create, Read, Update, Delete |
| **PSR** | PHP Standards Recommendations |

---

<div align="center">
  <p><strong>Documentación Técnica - BiblioTech v1.0</strong></p>
  <p>© 2024 - Guillen Cristofer</p>
  <p>Generado el: Diciembre 2024</p>
</div>
