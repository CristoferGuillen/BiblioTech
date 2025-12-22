@extends('layouts.librarian')

@section('title', 'Gestión de Préstamos')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Préstamos</h1>
            <p class="text-text-secondary">Administra los préstamos de libros</p>
        </div>
        <a href="{{ route('loans.create') }}" class="bg-accent hover:bg-opacity-90 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nuevo Préstamo
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $loans->where('status', 'ongoing')->count() }}</p>
                    <p class="text-sm text-text-secondary">Activos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $loans->where('status', 'returned')->count() }}</p>
                    <p class="text-sm text-text-secondary">Devueltos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $loans->where('status', 'overdue')->count() }}</p>
                    <p class="text-sm text-text-secondary">Vencidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" placeholder="Buscar por usuario o libro..." class="md:col-span-2 px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
            <select class="px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                <option value="">Todos los estados</option>
                <option value="active">Activos</option>
                <option value="returned">Devueltos</option>
                <option value="overdue">Vencidos</option>
            </select>
            <input type="date" class="px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-light-gray">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usuario</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Libro</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fecha Préstamo</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fecha Devolución</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-bg-main transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $loan->user->name }}</p>
                                    <p class="text-sm text-text-secondary">{{ $loan->user->email }}</p>
                                </div>
                            </div>
                        </td>
                            <td class="px-6 py-4">
                                @if($loan->book)
                                    <p class="font-semibold text-gray-800">
                                        {{ $loan->book->title }}
                                        @if($loan->book->trashed())
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded ml-2">Eliminado</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-text-secondary">{{ $loan->book->author }}</p>
                                @else
                                    <p class="text-gray-500 italic">Libro no disponible</p>
                                @endif
                            </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $loan->loan_date->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $loan->due_date->format('d/m/Y') }}</p>
                            @if($loan->status === 'ongoing' && $loan->due_date->isPast())
                                <p class="text-xs text-red-600 font-medium">Vencido</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                        @if($loan->status === 'ongoing')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                                En curso
                            </span>
                            @elseif($loan->status === 'returned')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Devuelto
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Vencido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('loans.show', $loan->id) }}" class="p-2 hover:bg-blue-50 rounded-lg transition" title="Ver">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @if($loan->status === 'ongoing')
                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 hover:bg-green-50 rounded-lg transition" title="Devolver">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('loans.renew', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 hover:bg-yellow-50 rounded-lg transition" title="Renovar">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-text-secondary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <p class="text-text-secondary text-lg">No hay préstamos registrados</p>
                            <a href="{{ route('loans.create') }}" class="text-accent hover:underline mt-2 inline-block">Crear el primer préstamo</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
