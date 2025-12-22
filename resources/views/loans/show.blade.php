@extends('layouts.librarian')

@section('title', 'Detalle del Préstamo')

@section('content')
<div class="fade-in max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('loans.index') }}" class="text-accent hover:underline flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a la lista
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Detalle del Préstamo</h1>
                <p class="text-text-secondary">Préstamo #{{ $loan->id }}</p>
            </div>
            <div class="flex gap-3">
                @if($loan->status === 'active')
                    <form action="{{ route('loans.renew', $loan->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Renovar (+7 días)
                        </button>
                    </form>

                    <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Marcar como Devuelto
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Estado del Préstamo</h2>
                    @if($loan->status === 'active')
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">
                            ⏳ En Curso
                        </span>
                    @elseif($loan->status === 'returned')
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                            ✓ Devuelto
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">
                            ⚠ Vencido
                        </span>
                    @endif
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Fecha de Préstamo</p>
                            <p class="text-text-secondary">{{ $loan->loan_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-accent bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Fecha de Devolución Esperada</p>
                            <p class="text-text-secondary">{{ $loan->due_date->format('d/m/Y') }}</p>
                            @if($loan->status === 'active')
                                @php
                                    $daysRemaining = now()->diffInDays($loan->due_date, false);
                                @endphp
                                @if($daysRemaining > 0)
                                    <p class="text-sm text-blue-600 mt-1">Quedan {{ $daysRemaining }} días</p>
                                @elseif($daysRemaining == 0)
                                    <p class="text-sm text-yellow-600 mt-1">¡Vence hoy!</p>
                                @else
                                    <p class="text-sm text-red-600 mt-1">Vencido hace {{ abs($daysRemaining) }} días</p>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($loan->return_date)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Fecha de Devolución Real</p>
                            <p class="text-text-secondary">{{ $loan->return_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Información del Usuario</h2>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $loan->user->name }}</h3>
                        <p class="text-text-secondary">{{ $loan->user->email }}</p>
                        <span class="inline-block mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium capitalize">
                            {{ $loan->user->role }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Book Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Información del Libro</h2>
                    <a href="{{ route('books.show', $loan->book->id) }}" class="text-accent hover:underline text-sm font-medium">
                        Ver detalles completos →
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Título</p>
                        <p class="font-semibold text-gray-800">{{ $loan->book->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Autor</p>
                        <p class="font-semibold text-gray-800">{{ $loan->book->author }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">ISBN</p>
                        <code class="bg-light-gray px-3 py-1 rounded text-sm">{{ $loan->book->isbn }}</code>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Copias Disponibles</p>
                        <p class="font-semibold text-gray-800">{{ $loan->book->copies_available }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            @if($loan->status === 'active')
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-medium transition">
                            ✓ Marcar Devuelto
                        </button>
                    </form>
                    <form action="{{ route('loans.renew', $loan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-medium transition">
                            🔄 Renovar Préstamo
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Additional Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información Adicional</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-text-secondary mb-1">ID del Préstamo</p>
                        <p class="font-semibold text-gray-800">#{{ $loan->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Registrado el</p>
                        <p class="font-semibold text-gray-800">{{ $loan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Última actualización</p>
                        <p class="font-semibold text-gray-800">{{ $loan->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="bg-light-gray rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-800 mb-2">Información</p>
                        <ul class="text-sm text-text-secondary space-y-1">
                            <li>• Los préstamos duran 14 días</li>
                            <li>• Se pueden renovar por 7 días más</li>
                            <li>• Al devolver se incrementa el stock</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
