@extends('layouts.librarian')

@section('title', 'Dashboard Bibliotecario')

@section('content')
<div class="animate-fade-in">  
<div class="fade-in">
    <!-- Header -->
        <div class="mb-8 animate-slide-in-left">
            <h2 class="text-3xl font-bold text-gray-800">Dashboard del Bibliotecario</h2>
            <p class="text-text-secondary mt-2">Bienvenido, {{ auth()->user()->name }}</p>
        </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Libros -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalBooks }}</h3>
            <p class="text-sm text-text-secondary">Libros registrados</p>
        </div>

        <!-- Libros Disponibles -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">Disponibles</span>
            </div>
            <h3 class="text-3xl font-bold text-green-600 mb-1">{{ $availableBooks }}</h3>
            <p class="text-sm text-text-secondary">Libros disponibles</p>
        </div>

        <!-- Libros No Disponibles -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-3">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">No disponibles</span>
            </div>
            <h3 class="text-3xl font-bold text-red-600 mb-1">{{ $unavailableBooks }}</h3>
            <p class="text-sm text-text-secondary">Sin stock</p>
        </div>

        <!-- Total Préstamos -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-4">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalLoans }}</h3>
            <p class="text-sm text-text-secondary">Préstamos totales</p>
        </div>

        <!-- Préstamos Activos -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-3">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">Activos</span>
            </div>
            <h3 class="text-3xl font-bold text-yellow-600 mb-1">{{ $ongoingLoans }}</h3>
            <p class="text-sm text-text-secondary">En curso</p>
        </div>

        <!-- Préstamos Vencidos -->
        <div class="stat-card rounded-xl p-6 card-hover animate-scale-in animate-stagger-4">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-text-secondary">Vencidos</span>
            </div>
            <h3 class="text-3xl font-bold text-accent mb-1">{{ $overdueLoans }}</h3>
            <p class="text-sm text-text-secondary">Requieren atención</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('books.create') }}" class="bg-white rounded-xl p-6 shadow-sm card-hover border-2 border-transparent hover:border-accent">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Agregar Libro</h3>
                    <p class="text-sm text-text-secondary">Registrar nuevo libro en el sistema</p>
                </div>
            </div>
        </a>

        <a href="{{ route('loans.create') }}" class="bg-white rounded-xl p-6 shadow-sm card-hover border-2 border-transparent hover:border-accent">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-accent rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Nuevo Préstamo</h3>
                    <p class="text-sm text-text-secondary">Registrar préstamo de libro</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Libros Populares -->
    @if(isset($popularBooks) && count($popularBooks) > 0)
   <div class="bg-white rounded-xl shadow-lg p-6 animate-on-scroll"> 
         <h3 class="text-xl font-bold text-gray-800 mb-4">📚 Libros Más Populares</h2>
        <div class="space-y-3">
            @foreach($popularBooks as $book)
            <div class="flex items-center justify-between p-4 bg-light-gray rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-accent rounded-lg flex items-center justify-center text-white font-bold">
                        #{{ $loop->iteration }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $book->title }}</h3>
                        <p class="text-sm text-text-secondary">{{ $book->author }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-accent">{{ $book->loans_count }}</p>
                    <p class="text-xs text-text-secondary">préstamos</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
</div> 
@endsection
