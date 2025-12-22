<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibrarianDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Sin autenticación)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Ruta de No Autorizado
|--------------------------------------------------------------------------
*/

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');


/*
|--------------------------------------------------------------------------
| Rutas Autenticadas (Cualquier usuario logueado)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard general (redirige según el rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});


/*
|--------------------------------------------------------------------------
| Rutas para LIBRARIAN y ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.role:librarian,admin'])->group(function () {
    
    // Dashboard del bibliotecario
    Route::get('/librarian/dashboard', [LibrarianDashboardController::class, 'index'])
        ->name('librarian.dashboard');
    
    
    // ============================================
    // GESTIÓN DE LIBROS (CRUD completo)
    // ============================================
    
    // Rutas resource automáticas
    Route::resource('books', BookController::class);
    
    // Rutas personalizadas para libros
    Route::patch('/books/{id}/status', [BookController::class, 'updateStatus'])
        ->name('books.updateStatus');
    
    Route::patch('/books/{id}/copies', [BookController::class, 'updateCopies'])
        ->name('books.updateCopies');
    Route::patch('/books/{id}/restore', [BookController::class, 'restore'])
        ->name('books.restore');
    
    
    // ============================================
    // GESTIÓN DE PRÉSTAMOS (CRUD + acciones)
    // ============================================
    
    // Rutas resource automáticas
    Route::resource('loans', LoanController::class);
    
    // Rutas personalizadas para préstamos
    Route::patch('/loans/{id}/return', [LoanController::class, 'return'])
        ->name('loans.return');
    
    Route::patch('/loans/{id}/renew', [LoanController::class, 'renew'])
        ->name('loans.renew');
});


/*
|--------------------------------------------------------------------------
| Rutas solo para ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.role:admin'])->group(function () {
    
    // Dashboard del administrador
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Gestión de usuarios
    Route::get('/admin/users', function () {
        return view('admin.users');
    })->name('admin.users');
    
    // Configuración del sistema
    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
    
    // Reportes avanzados
    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');
});


/*
|--------------------------------------------------------------------------
| Rutas solo para MEMBER (usuarios normales)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.role:member'])->group(function () {
    
    // Dashboard del usuario
    Route::get('/member/dashboard', function () {
        return view('member.dashboard');
    })->name('member.dashboard');
    
    // Ver mis préstamos
    Route::get('/member/loans', function () {
        return view('member.loans');
    })->name('member.loans');
    
    // Reservar libros
    Route::get('/member/reservations', function () {
        return view('member.reservations');
    })->name('member.reservations');
});
