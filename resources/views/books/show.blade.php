@extends('layouts.librarian')

@section('title', 'Detalle del Libro')

@section('content')
<div class="fade-in max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('books.index') }}" class="text-accent hover:underline flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a la lista
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->title }}</h1>
                <p class="text-text-secondary">Información detallada del libro</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('books.edit', $book->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este libro?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Book Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Información del Libro</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Título</p>
                        <p class="font-semibold text-gray-800">{{ $book->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Autor</p>
                        <p class="font-semibold text-gray-800">{{ $book->author }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">ISBN</p>
                        <code class="bg-light-gray px-3 py-1 rounded font-mono text-sm">{{ $book->isbn }}</code>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Año de Publicación</p>
                        <p class="font-semibold text-gray-800">{{ $book->published_year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Categoría</p>
                        @if($book->category)
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $book->category->name }}
                            </span>
                        @else
                            <span class="text-text-secondary">Sin categoría</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Fecha de Registro</p>
                        <p class="font-semibold text-gray-800">{{ $book->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Loans History -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Historial de Préstamos</h2>
                @if($book->loans && count($book->loans) > 0)
                    <div class="space-y-3">
                        @foreach($book->loans->take(5) as $loan)
                        <div class="flex items-center justify-between p-4 bg-light-gray rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $loan->user->name }}</p>
                                <p class="text-sm text-text-secondary">{{ $loan->loan_date->format('d/m/Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $loan->status === 'active' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $loan->status === 'returned' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $loan->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-text-secondary text-center py-8">No hay préstamos registrados para este libro</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <!-- Availability -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Disponibilidad</h3>
                <div class="text-center mb-4">
                    <div class="w-24 h-24 mx-auto bg-accent bg-opacity-10 rounded-full flex items-center justify-center mb-3">
                        <span class="text-4xl font-bold text-accent">{{ $book->copies_available }}</span>
                    </div>
                    <p class="text-text-secondary">Copias disponibles</p>
                </div>
                <div class="pt-4 border-t border-light-gray">
                    @if($book->status === 'available')
                        <div class="flex items-center gap-2 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Libro Disponible</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">No Disponible</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    @if($book->copies_available > 0)
                    <a href="{{ route('loans.create') }}?book_id={{ $book->id }}" class="block w-full bg-accent hover:bg-opacity-90 text-white text-center px-4 py-3 rounded-lg font-medium transition">
                        Crear Préstamo
                    </a>
                    @endif
                    <form action="{{ route('books.updateStatus', $book->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $book->status === 'available' ? 'unavailable' : 'available' }}">
                        <button type="submit" class="w-full bg-light-gray hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium transition">
                            {{ $book->status === 'available' ? 'Marcar No Disponible' : 'Marcar Disponible' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Estadísticas</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-text-secondary">Total de préstamos</span>
                        <span class="font-bold text-gray-800">{{ $book->loans ? count($book->loans) : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-text-secondary">Préstamos activos</span>
                        <span class="font-bold text-yellow-600">{{ $book->loans ? $book->loans->where('status', 'active')->count() : 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-text-secondary">Devueltos</span>
                        <span class="font-bold text-green-600">{{ $book->loans ? $book->loans->where('status', 'returned')->count() : 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
