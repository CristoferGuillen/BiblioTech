@extends('layouts.librarian')

@section('title', 'Gestión de Libros')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Libros</h1>
            <p class="text-text-secondary">Administra el catálogo de la biblioteca</p>
        </div>
        <a href="{{ route('books.create') }}" class="bg-accent hover:bg-opacity-90 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Agregar Libro
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <input type="text" placeholder="Buscar por título, autor o ISBN..." class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
            </div>
            <select class="px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                <option value="">Todos los estados</option>
                <option value="available">Disponibles</option>
                <option value="unavailable">No disponibles</option>
            </select>
        </div>
    </div>

    <!-- Books Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-light-gray">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Libro</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Autor</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ISBN</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Categoría</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Copias</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($books as $book)
                    <tr class="hover:bg-bg-main transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $book->title }}</p>
                                <p class="text-sm text-text-secondary">Publicado: {{ $book->published_year }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $book->author }}</td>
                        <td class="px-6 py-4">
                            <code class="bg-light-gray px-2 py-1 rounded text-sm">{{ $book->isbn }}</code>
                        </td>
                        <td class="px-6 py-4">
                            @if($book->category)
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $book->category->name }}
                                </span>
                            @else
                                <span class="text-text-secondary text-sm">Sin categoría</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold {{ $book->copies_available > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $book->copies_available }}
                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                @if($book->trashed())
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                                        Eliminado
                                    </span>
                                @elseif($book->status === 'available')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                        Disponible
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                        No disponible
                                    </span>
                                @endif
                            </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @if($book->trashed())
                                    <!-- Botón de Restaurar -->
                                    <form action="{{ route('books.restore', $book->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 hover:bg-green-50 rounded-lg transition" title="Restaurar">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <!-- Ver -->
                                    <a href="{{ route('books.show', $book->id) }}" class="p-2 hover:bg-blue-50 rounded-lg transition" title="Ver">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Editar -->
                                    <a href="{{ route('books.edit', $book->id) }}" class="p-2 hover:bg-yellow-50 rounded-lg transition" title="Editar">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Eliminar -->
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este libro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-text-secondary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="text-text-secondary text-lg">No hay libros registrados</p>
                            <a href="{{ route('books.create') }}" class="text-accent hover:underline mt-2 inline-block">Agregar el primer libro</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
