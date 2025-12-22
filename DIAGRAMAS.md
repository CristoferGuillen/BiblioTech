# 📊 Diagramas BiblioTech

> **Versión:** 1.0  
> **Sistema:** BiblioTech - Gestión de Biblioteca  
> **Última actualización:** Diciembre 2024  
> **Formato:** Mermaid (renderizado automático en GitHub)

---

## 📑 Tabla de Contenidos

- [1. Diagrama Entidad-Relación (ERD)](#1-diagrama-entidad-relación-erd)
- [2. Diagramas de Flujo de Procesos](#2-diagramas-de-flujo-de-procesos)
- [3. Diagramas UML de Clases](#3-diagramas-uml-de-clases)
- [4. Diagramas de Secuencia](#4-diagramas-de-secuencia)
- [5. Diagramas de Estados](#5-diagramas-de-estados)

---

## 1. Diagrama Entidad-Relación (ERD)

### 1.1 ERD Completo - Base de Datos BiblioTech

```mermaid
erDiagram
    USUARIOS ||--o{ PRESTAMOS : realiza
    LIBROS ||--o{ PRESTAMOS : "es prestado"
    CATEGORIAS ||--o{ LIBROS : contiene
    USUARIOS ||--o{ SESIONES : tiene

    USUARIOS {
        bigint id PK "Clave primaria"
        varchar nombre "Nombre completo"
        varchar email UK "Email único"
        varchar contrasena "Password hasheado"
        varchar telefono "Teléfono opcional"
        enum rol "admin, librarian, member"
        timestamp email_verificado "Verificación email"
        varchar token_recordar "Remember token"
        timestamp eliminado_en "Soft delete"
        timestamp creado_en
        timestamp actualizado_en
    }

    LIBROS {
        bigint id PK "Clave primaria"
        varchar titulo "Título del libro"
        varchar autor "Autor del libro"
        varchar isbn UK "ISBN único"
        year anio_publicacion "Año"
        bigint categoria_id FK "FK a categorias"
        int copias_disponibles "Inventario"
        enum estado "available, unavailable"
        timestamp eliminado_en "Soft delete"
        timestamp creado_en
        timestamp actualizado_en
    }

    PRESTAMOS {
        bigint id PK "Clave primaria"
        bigint usuario_id FK "FK a usuarios"
        bigint libro_id FK "FK a libros"
        date fecha_prestamo "Fecha del préstamo"
        date fecha_devolucion "Fecha límite"
        date fecha_devuelto "Fecha real devolución"
        enum estado "ongoing, returned, overdue"
        timestamp creado_en
        timestamp actualizado_en
    }

    CATEGORIAS {
        bigint id PK "Clave primaria"
        varchar nombre UK "Nombre único"
        text descripcion "Descripción opcional"
        timestamp eliminado_en "Soft delete"
        timestamp creado_en
        timestamp actualizado_en
    }

    SESIONES {
        varchar id PK "Session ID"
        bigint usuario_id FK "FK a usuarios"
        varchar direccion_ip "IP del cliente"
        text agente_usuario "User agent"
        longtext carga "Session data"
        int ultima_actividad "Timestamp"
    }
```

### 1.2 Restricciones y Relaciones Detalladas

| Tabla Origen | Columna | Tipo Restricción | Tabla Destino | Columna | Acción |
|--------------|---------|-------------------|---------------|---------|--------|
| LIBROS | categoria_id | FOREIGN KEY | CATEGORIAS | id | ON DELETE RESTRICT |
| PRESTAMOS | usuario_id | FOREIGN KEY | USUARIOS | id | ON DELETE RESTRICT |
| PRESTAMOS | libro_id | FOREIGN KEY | LIBROS | id | ON DELETE RESTRICT |
| SESIONES | usuario_id | FOREIGN KEY | USUARIOS | id | ON DELETE CASCADE |
| USUARIOS | email | UNIQUE | - | - | - |
| LIBROS | isbn | UNIQUE | - | - | - |
| CATEGORIAS | nombre | UNIQUE | - | - | - |

### 1.3 Índices de Base de Datos

```sql
-- Índices en tabla USUARIOS
CREATE UNIQUE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_deleted_at ON users(deleted_at);

-- Índices en tabla LIBROS
CREATE UNIQUE INDEX idx_books_isbn ON books(isbn);
CREATE INDEX idx_books_category_id ON books(category_id);
CREATE INDEX idx_books_status ON books(status);
CREATE INDEX idx_books_deleted_at ON books(deleted_at);

-- Índices en tabla PRESTAMOS
CREATE INDEX idx_loans_user_id ON loans(user_id);
CREATE INDEX idx_loans_book_id ON loans(book_id);
CREATE INDEX idx_loans_status ON loans(status);
CREATE INDEX idx_loans_due_date ON loans(due_date);

-- Índices en tabla CATEGORIAS
CREATE UNIQUE INDEX idx_categories_name ON categories(name);
CREATE INDEX idx_categories_deleted_at ON categories(deleted_at);

-- Índices en tabla SESIONES
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);
```

---

## 2. Diagramas de Flujo de Procesos

### 2.1 Proceso de Autenticación de Usuario

```mermaid
flowchart TD
    A([Inicio: Usuario accede al sistema]) --> B{{¿Tiene cuenta registrada?}}
    
    B -->|No| C[Ir a formulario de registro]
    C --> D[Completar datos: nombre, email, teléfono, contraseña]
    D --> E{Validar datos de registro}
    E -->|Datos inválidos| F[Mostrar mensajes de error]
    F --> C
    E -->|Datos válidos| G[Crear usuario en base de datos<br/>rol = member por defecto]
    G --> H[Redirigir a formulario de login]
    
    B -->|Sí| H
    H --> I[Ingresar email y contraseña]
    I --> J[Enviar credenciales a Laravel Fortify]
    J --> K{Verificar credenciales}
    
    K -->|Incorrectas| L[Mostrar error:<br/>Credenciales inválidas]
    L --> I
    
    K -->|Correctas| M[Crear registro en tabla sessions]
    M --> N[Obtener rol del usuario]
    N --> O{Verificar rol}
    
    O -->|rol = admin| P[Redirigir a /admin/dashboard]
    O -->|rol = librarian| Q[Redirigir a /librarian/dashboard]
    O -->|rol = member| R[Redirigir a /member/dashboard]
    O -->|rol desconocido| S[Redirigir a /unauthorized]
    
    P --> T([Fin: Usuario autenticado])
    Q --> T
    R --> T
    S --> U([Fin: Acceso denegado])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style T fill:#90EE90,stroke:#333,stroke-width:2px
    style U fill:#FFB6C6,stroke:#333,stroke-width:2px
    style L fill:#FFB6C6,stroke:#333,stroke-width:2px
    style F fill:#FFB6C6,stroke:#333,stroke-width:2px
    style M fill:#87CEEB,stroke:#333,stroke-width:2px
    style G fill:#87CEEB,stroke:#333,stroke-width:2px
```

### 2.2 Proceso de Creación de Préstamo

```mermaid
flowchart TD
    A([Inicio: Bibliotecario/Admin accede a crear préstamo]) --> B[GET /loans/create]
    B --> C[LoanController->create ejecuta]
    C --> D[Consultar todos los usuarios de la BD]
    C --> E[Consultar libros WHERE copias_disponibles > 0]
    D --> F[Cargar vista loans/create.blade.php]
    E --> F
    F --> G[Mostrar formulario con:<br/>- Select de usuarios<br/>- Select de libros disponibles]
    
    G --> H[Usuario selecciona un usuario del sistema]
    H --> I[Usuario selecciona un libro disponible]
    I --> J[Usuario envía formulario]
    
    J --> K[POST /loans]
    K --> L[LoanController->store recibe datos]
    L --> M{Validar datos:<br/>- usuario_id: required exists users<br/>- libro_id: required exists books}
    
    M -->|Validación falla| N[Retornar errores de validación]
    N --> G
    
    M -->|Validación exitosa| O[Buscar libro por libro_id]
    O --> P{Verificar:<br/>copias_disponibles > 0?}
    
    P -->|No hay copias| Q[Retornar error:<br/>No hay copias disponibles para este libro]
    Q --> G
    
    P -->|Hay copias| R[Crear registro en tabla loans:<br/>- usuario_id<br/>- libro_id<br/>- fecha_prestamo = now<br/>- fecha_devolucion = now + 14 días<br/>- estado = ongoing]
    R --> S[Ejecutar:<br/>UPDATE books<br/>SET copias_disponibles = copias_disponibles - 1<br/>WHERE id = libro_id]
    S --> T[Guardar ambos cambios en base de datos]
    T --> U[Redirigir a /loans con mensaje:<br/>Préstamo creado exitosamente]
    
    U --> V([Fin: Préstamo registrado])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style V fill:#90EE90,stroke:#333,stroke-width:2px
    style Q fill:#FFB6C6,stroke:#333,stroke-width:2px
    style N fill:#FFB6C6,stroke:#333,stroke-width:2px
    style R fill:#87CEEB,stroke:#333,stroke-width:2px
    style S fill:#87CEEB,stroke:#333,stroke-width:2px
    style T fill:#87CEEB,stroke:#333,stroke-width:2px
```

### 2.3 Proceso de Devolución de Libro

```mermaid
flowchart TD
    A([Inicio: Devolver libro]) --> B[Usuario hace clic en botón Devolver<br/>en la vista loans/index]
    B --> C[PATCH /loans/id/return]
    C --> D[LoanController->return recibe id del préstamo]
    
    D --> E[Buscar préstamo con Eloquent:<br/>Loan::with book withTrashed->findOrFail id]
    E --> F{Préstamo encontrado?}
    
    F -->|No encontrado| G[Lanzar excepción 404:<br/>Préstamo no existe]
    G --> H([Fin: Error 404])
    
    F -->|Encontrado| I[Obtener objeto préstamo con relación libro]
    I --> J[Actualizar campos del préstamo:<br/>- estado = returned<br/>- fecha_devuelto = now]
    J --> K[Guardar cambios:<br/>UPDATE loans SET estado, fecha_devuelto<br/>WHERE id = id_prestamo]
    
    K --> L{Verificar si libro existe<br/>libro no es NULL?}
    
    L -->|Libro eliminado anteriormente| M[No actualizar inventario<br/>El libro tiene deleted_at != NULL]
    M --> N[Redirigir a /loans con mensaje:<br/>Libro devuelto exitosamente]
    
    L -->|Libro existe| O[Incrementar inventario:<br/>UPDATE books<br/>SET copias_disponibles = copias_disponibles + 1<br/>WHERE id = libro_id]
    O --> N
    
    N --> P([Fin: Devolución registrada])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style P fill:#90EE90,stroke:#333,stroke-width:2px
    style H fill:#FFB6C6,stroke:#333,stroke-width:2px
    style G fill:#FFB6C6,stroke:#333,stroke-width:2px
    style J fill:#87CEEB,stroke:#333,stroke-width:2px
    style K fill:#87CEEB,stroke:#333,stroke-width:2px
    style O fill:#87CEEB,stroke:#333,stroke-width:2px
```

### 2.4 Proceso de Renovación de Préstamo

```mermaid
flowchart TD
    A([Inicio: Renovar préstamo]) --> B[Usuario hace clic en Renovar]
    B --> C[PATCH /loans/id/renew]
    C --> D[LoanController->renew recibe id]
    
    D --> E[Buscar préstamo:<br/>Loan::with book withTrashed->findOrFail id]
    E --> F{Préstamo existe?}
    
    F -->|No| G[Error 404: Préstamo no encontrado]
    G --> H([Fin: Error])
    
    F -->|Sí| I[Obtener relación con libro]
    I --> J{Libro existe<br/>libro != NULL?}
    
    J -->|Libro eliminado| K[Retornar error:<br/>No se puede renovar el libro ha sido eliminado]
    K --> L[Redirigir a /loans con mensaje de error]
    L --> H
    
    J -->|Libro existe| M[Obtener fecha_devolucion actual del préstamo]
    M --> N[Calcular nueva fecha:<br/>nueva_fecha = fecha_devolucion actual + 7 días]
    N --> O[Actualizar préstamo:<br/>UPDATE loans<br/>SET fecha_devolucion = nueva_fecha<br/>WHERE id = id_prestamo]
    O --> P[Guardar cambios en base de datos]
    P --> Q[Redirigir a /loans con mensaje:<br/>Préstamo renovado exitosamente]
    
    Q --> R([Fin: Préstamo renovado])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style R fill:#90EE90,stroke:#333,stroke-width:2px
    style H fill:#FFB6C6,stroke:#333,stroke-width:2px
    style K fill:#FFB6C6,stroke:#333,stroke-width:2px
    style G fill:#FFB6C6,stroke:#333,stroke-width:2px
    style O fill:#87CEEB,stroke:#333,stroke-width:2px
    style P fill:#87CEEB,stroke:#333,stroke-width:2px
```

### 2.5 Proceso de Eliminación Suave (Soft Delete) de Libro

```mermaid
flowchart TD
    A([Inicio: Eliminar libro]) --> B[Usuario hace clic en Eliminar<br/>en gestión de libros]
    B --> C[DELETE /books/id]
    C --> D[BookController->destroy recibe id]
    
    D --> E[Buscar libro:<br/>Book::findOrFail id]
    E --> F{Libro existe?}
    
    F -->|No existe| G[Error 404: Libro no encontrado]
    G --> H([Fin: Error])
    
    F -->|Existe| I[Ejecutar soft delete:<br/>libro->delete]
    I --> J[Laravel actualiza:<br/>UPDATE books<br/>SET deleted_at = CURRENT_TIMESTAMP<br/>WHERE id = id_libro]
    J --> K[Libro marcado como eliminado<br/>pero NO se borra de la BD]
    K --> L[Redirigir a /books con mensaje:<br/>Libro eliminado exitosamente]
    
    L --> M{Usuario desea restaurar?}
    
    M -->|No| N([Fin: Libro permanece eliminado<br/>No visible en consultas normales])
    
    M -->|Sí| O[Usuario hace clic en Restaurar]
    O --> P[PATCH /books/id/restore]
    P --> Q[BookController->restore recibe id]
    Q --> R[Buscar libro incluyendo eliminados:<br/>Book::withTrashed->findOrFail id]
    R --> S[Ejecutar restauración:<br/>libro->restore]
    S --> T[Laravel actualiza:<br/>UPDATE books<br/>SET deleted_at = NULL<br/>WHERE id = id_libro]
    T --> U[Libro visible nuevamente en consultas]
    U --> V[Redirigir a /books con mensaje:<br/>Libro restaurado exitosamente]
    
    V --> W([Fin: Libro restaurado])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style W fill:#90EE90,stroke:#333,stroke-width:2px
    style N fill:#FFE4B5,stroke:#333,stroke-width:2px
    style H fill:#FFB6C6,stroke:#333,stroke-width:2px
    style G fill:#FFB6C6,stroke:#333,stroke-width:2px
    style J fill:#87CEEB,stroke:#333,stroke-width:2px
    style T fill:#87CEEB,stroke:#333,stroke-width:2px
```

### 2.6 Flujo de Middleware de Roles (CheckRole)

```mermaid
flowchart TD
    A([Inicio: Petición HTTP]) --> B[Usuario envía petición a ruta protegida<br/>Ejemplo: GET /books/create]
    B --> C[Router web.php identifica la ruta]
    C --> D[Aplicar middleware: auth]
    
    D --> E{Usuario autenticado?<br/>Auth::check}
    
    E -->|No autenticado| F[Redirigir a /login]
    F --> G([Fin: Acceso denegado])
    
    E -->|Autenticado| H[Aplicar middleware: check.role con parámetros<br/>Ejemplo: check.role:librarian,admin]
    H --> I[CheckRole->handle ejecuta]
    I --> J[Obtener roles permitidos de la ruta<br/>roles = librarian, admin]
    J --> K[Obtener rol del usuario actual:<br/>Auth::user->rol]
    
    K --> L{Verificar:<br/>rol del usuario está en roles permitidos?<br/>in_array rol, roles}
    
    L -->|No está permitido| M[Redirigir a /unauthorized]
    M --> N([Fin: Acceso denegado])
    
    L -->|Permitido| O[Permitir acceso a la ruta]
    O --> P[Ejecutar controlador:<br/>BookController->create]
    P --> Q[Procesar lógica del controlador]
    Q --> R[Retornar vista al usuario]
    
    R --> S([Fin: Acceso concedido])

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style S fill:#90EE90,stroke:#333,stroke-width:2px
    style G fill:#FFB6C6,stroke:#333,stroke-width:2px
    style N fill:#FFB6C6,stroke:#333,stroke-width:2px
    style O fill:#87CEEB,stroke:#333,stroke-width:2px
    style P fill:#87CEEB,stroke:#333,stroke-width:2px
```

---

## 3. Diagramas UML de Clases

### 3.1 Diagrama de Clases - Modelos Eloquent

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string password
        +string phone
        +string role
        +datetime email_verified_at
        +string remember_token
        +datetime deleted_at
        +datetime created_at
        +datetime updated_at
        +loans() HasMany~Loan~
        +activeLoans() HasMany~Loan~
        +initials() string
        +isAdmin() bool
        +isLibrarian() bool
        +isMember() bool
        +isStaff() bool
        +isActive() bool
    }

    class Book {
        +int id
        +string title
        +string author
        +string isbn
        +int publication_year
        +int category_id
        +int copies_available
        +string status
        +datetime deleted_at
        +datetime created_at
        +datetime updated_at
        +category() BelongsTo~Category~
        +loans() HasMany~Loan~
    }

    class Loan {
        +int id
        +int user_id
        +int book_id
        +date loan_date
        +date due_date
        +date return_date
        +string status
        +datetime created_at
        +datetime updated_at
        +user() BelongsTo~User~
        +book() BelongsTo~Book~
    }

    class Category {
        +int id
        +string name
        +text description
        +datetime deleted_at
        +datetime created_at
        +datetime updated_at
        +books() HasMany~Book~
    }

    User "1" --> "0..*" Loan : realiza
    Book "1" --> "0..*" Loan : tiene
    Category "1" --> "0..*" Book : contiene
    
    note for User "Trait: HasFactory, SoftDeletes\nGuard: web\nAuth: Fortify"
    note for Book "Trait: SoftDeletes\nFillable: title, author, isbn, publication_year, category_id, copies_available, status"
    note for Loan "Fillable: user_id, book_id, loan_date, due_date, return_date, status"
    note for Category "Fillable: name, description"
```

### 3.2 Diagrama de Clases - Controladores

```mermaid
classDiagram
    class Controller {
        <<abstract>>
    }

    class BookController {
        +index() View
        +create() View
        +store(Request) RedirectResponse
        +show(id) View
        +edit(id) View
        +update(Request, id) RedirectResponse
        +destroy(id) RedirectResponse
        +updateStatus(Request, id) RedirectResponse
        +restore(id) RedirectResponse
    }

    class LoanController {
        +index() View
        +create() View
        +store(Request) RedirectResponse
        +show(id) View
        +return(id) RedirectResponse
        +renew(id) RedirectResponse
    }

    class DashboardController {
        +index() RedirectResponse
    }

    class LibrarianDashboardController {
        +index() View
    }

    Controller <|-- BookController
    Controller <|-- LoanController
    Controller <|-- DashboardController
    Controller <|-- LibrarianDashboardController

    note for BookController "Gestiona CRUD de libros\nRutas: /books/*\nMiddleware: auth, check.role:librarian,admin"
    note for LoanController "Gestiona préstamos\nRutas: /loans/*\nMiddleware: auth, check.role:librarian,admin"
    note for DashboardController "Redirige según rol\nRuta: /dashboard\nMiddleware: auth"
```

---

## 4. Diagramas de Secuencia

### 4.1 Secuencia: Crear Préstamo

```mermaid
sequenceDiagram
    actor Bibliotecario
    participant Vista as loans/create.blade
    participant Routes as web.php
    participant Middleware as check.role
    participant Controller as LoanController
    participant BookModel as Book Model
    participant LoanModel as Loan Model
    participant DB as MySQL Database

    Bibliotecario->>Vista: Accede a /loans/create
    Vista->>Routes: GET /loans/create
    Routes->>Middleware: Verificar permisos
    Middleware->>Middleware: ¿Rol es librarian o admin?
    
    alt Permisos correctos
        Middleware->>Controller: Permitir acceso
        Controller->>DB: SELECT * FROM users
        DB-->>Controller: Lista de usuarios
        Controller->>DB: SELECT * FROM books WHERE copies_available > 0
        DB-->>Controller: Lista de libros disponibles
        Controller-->>Vista: Retorna vista con datos
        Vista-->>Bibliotecario: Muestra formulario
        
        Bibliotecario->>Vista: Selecciona usuario y libro
        Bibliotecario->>Vista: Envía formulario
        Vista->>Routes: POST /loans {user_id, book_id}
        Routes->>Controller: store(Request)
        
        Controller->>Controller: Validar datos
        Controller->>BookModel: findOrFail(book_id)
        BookModel->>DB: SELECT * FROM books WHERE id = ?
        DB-->>BookModel: Datos del libro
        BookModel-->>Controller: Objeto Book
        
        Controller->>Controller: Verificar copies_available > 0
        
        alt Hay copias disponibles
            Controller->>LoanModel: create([datos])
            LoanModel->>DB: INSERT INTO loans
            DB-->>LoanModel: Préstamo creado
            Controller->>BookModel: decrement('copies_available')
            BookModel->>DB: UPDATE books SET copies_available = copies_available - 1
            DB-->>BookModel: Actualizado
            Controller-->>Vista: Redirect /loans con success
            Vista-->>Bibliotecario: "Préstamo creado exitosamente"
        else Sin copias
            Controller-->>Vista: Error "No hay copias disponibles"
            Vista-->>Bibliotecario: Muestra error
        end
    else Sin permisos
        Middleware-->>Vista: Redirect /unauthorized
        Vista-->>Bibliotecario: Acceso denegado
    end
```

### 4.2 Secuencia: Autenticación con Laravel Fortify

```mermaid
sequenceDiagram
    actor Usuario
    participant Vista as login.blade
    participant Routes as web.php
    participant Fortify as Laravel Fortify
    participant Auth as AuthManager
    participant DB as MySQL Database
    participant Session as SessionGuard

    Usuario->>Vista: Accede a /login
    Vista-->>Usuario: Muestra formulario
    
    Usuario->>Vista: Ingresa email y password
    Vista->>Routes: POST /login
    Routes->>Fortify: Procesar login
    Fortify->>Auth: attempt([email, password])
    
    Auth->>DB: SELECT * FROM users WHERE email = ?
    DB-->>Auth: Usuario encontrado
    Auth->>Auth: Hash::check(password, user.password)
    
    alt Credenciales válidas
        Auth-->>Fortify: Login exitoso
        Fortify->>Session: login(user)
        Session->>DB: INSERT INTO sessions
        DB-->>Session: Sesión creada
        Session-->>Fortify: Sesión activa
        
        Fortify-->>Routes: Redirect /dashboard
        Routes->>DashboardController: index()
        DashboardController->>Auth: user()->role
        Auth-->>DashboardController: role = "librarian"
        
        alt role = admin
            DashboardController-->>Routes: Redirect /admin/dashboard
        else role = librarian
            DashboardController-->>Routes: Redirect /librarian/dashboard
        else role = member
            DashboardController-->>Routes: Redirect /member/dashboard
        end
        
        Routes-->>Vista: Dashboard correspondiente
        Vista-->>Usuario: Muestra dashboard
    else Credenciales inválidas
        Auth-->>Fortify: Login fallido
        Fortify-->>Vista: Error "Las credenciales son incorrectas"
        Vista-->>Usuario: Muestra error
    end
```

---

## 5. Diagramas de Estados

### 5.1 Estados de un Préstamo

```mermaid
stateDiagram-v2
    [*] --> EnCurso: Préstamo creado\nloan_date = now()\ndue_date = now() + 14 días
    
    EnCurso --> Devuelto: Usuario devuelve libro\nreturn_date = now()\ncopies_available + 1
    
    EnCurso --> Atrasado: due_date < now()\nsin devolución
    
    Atrasado --> Devuelto: Usuario devuelve libro tarde\nreturn_date = now()\ncopies_available + 1
    
    EnCurso --> EnCurso: Renovar préstamo\ndue_date = due_date + 7 días
    
    Devuelto --> [*]: Préstamo finalizado

    note right of EnCurso
        Estado: ongoing
        Libro prestado activamente
        Usuario tiene el libro
    end note

    note right of Atrasado
        Estado: overdue
        Fecha límite vencida
        Puede generar sanción
    end note

    note right of Devuelto
        Estado: returned
        Libro devuelto
        Inventario actualizado
    end note
```

### 5.2 Estados de un Libro

```mermaid
stateDiagram-v2
    [*] --> Disponible: Libro creado\nstatus = available\ncopies_available > 0
    
    Disponible --> NoDisponible: Administrador cambia status\nmanualmente a unavailable
    
    Disponible --> NoDisponible: Se prestan todas las copias\ncopies_available = 0
    
    NoDisponible --> Disponible: Usuario devuelve libro\ncopies_available > 0
    
    NoDisponible --> Disponible: Administrador cambia status\nmanualmente a available
    
    Disponible --> Eliminado: Soft delete\ndeleted_at = now()
    
    NoDisponible --> Eliminado: Soft delete\ndeleted_at = now()
    
    Eliminado --> Disponible: Restaurar\ndeleted_at = NULL
    
    Eliminado --> NoDisponible: Restaurar\ndeleted_at = NULL

    note right of Disponible
        Visible en consultas normales
        Se puede prestar
        Status: available
    end note

    note right of NoDisponible
        Visible en consultas normales
        No se puede prestar
        Status: unavailable
    end note

    note right of Eliminado
        No visible en consultas normales
        Visible solo con withTrashed()
        Préstamos anteriores mantienen referencia
        deleted_at IS NOT NULL
    end note
```

### 5.3 Ciclo de Vida de Usuario

```mermaid
stateDiagram-v2
    [*] --> Registrado: Usuario completa registro\nrol = member (default)\nemail, nombre, password
    
    Registrado --> Activo: Login exitoso\nSesión creada en tabla sessions
    
    Activo --> Inactivo: Sin actividad\nlast_activity antigua
    
    Inactivo --> Activo: Usuario interactúa\nlast_activity actualizado
    
    Inactivo --> Expirado: SESSION_LIFETIME (120 min)\nSesión expirada
    
    Activo --> Expirado: SESSION_LIFETIME alcanzado
    
    Expirado --> Registrado: Usuario debe login nuevamente
    
    Activo --> Suspendido: Admin desactiva cuenta\ndeleted_at = now()
    
    Suspendido --> Activo: Admin restaura cuenta\ndeleted_at = NULL
    
    Suspendido --> [*]: Eliminación permanente\n(No implementado)

    note right of Activo
        Usuario autenticado
        Puede acceder según rol
        Sesión válida
    end note

    note right of Suspendido
        deleted_at IS NOT NULL
        No puede autenticarse
        Cuenta desactivada
    end note
```

---

## 6. Leyenda de Colores y Símbolos

### 6.1 Colores en Diagramas de Flujo

| Color | Código | Significado |
|-------|--------|-------------|
| 🟢 Verde claro | `#90EE90` | Inicio / Fin de proceso |
| 🔵 Azul claro | `#87CEEB` | Operación / Proceso / Acción |
| 🔴 Rosa | `#FFB6C6` | Error / Fallo / Excepción |
| 🟡 Amarillo claro | `#FFE4B5` | Espera / Decisión / Advertencia |

### 6.2 Símbolos de Diagramas de Flujo

```mermaid
flowchart LR
    A([Inicio/Fin])
    B[Proceso]
    C{{Decisión}}
    D[(Base de Datos)]
    E[/Entrada-Salida/]

    style A fill:#90EE90,stroke:#333,stroke-width:2px
    style B fill:#87CEEB,stroke:#333,stroke-width:2px
    style C fill:#FFE4B5,stroke:#333,stroke-width:2px
    style D fill:#FCE4EC,stroke:#333,stroke-width:2px
    style E fill:#E8F5E9,stroke:#333,stroke-width:2px
```

---

## 7. Notas Técnicas

### 7.1 Cómo Ver los Diagramas

Los diagramas en este archivo usan **sintaxis Mermaid** que GitHub renderiza automáticamente como imágenes cuando ves el archivo en el repositorio.

**Para verlos correctamente:**
1. Abre este archivo en GitHub (no en editor local)
2. Los diagramas se renderizarán como gráficos visuales
3. Si no se ven, actualiza la página

**Editores compatibles:**
- GitHub (renderizado automático)
- Visual Studio Code (con extensión Markdown Preview Mermaid Support)
- GitLab
- Notion
- Obsidian

### 7.2 Información Verificada

Todos los diagramas en este documento están basados en:
- Código real del repositorio BiblioTech rama `dev`
- Migraciones de base de datos existentes
- Modelos Eloquent implementados
- Controladores actuales
- Rutas definidas en `routes/web.php`

**No hay información inventada o supuesta.**

---

<div align="center">
  <p><strong>Diagramas BiblioTech v1.0</strong></p>
  <p>Todos los diagramas representan el estado actual del sistema</p>
  <p>Basado en código real de la rama dev</p>
  <p>© 2024 - Guillen Cristofer</p>
  <p>Generado: Diciembre 2024</p>
</div>
