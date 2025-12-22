@extends('layouts.librarian')

@section('title', 'Editar Libro')

@section('content')
<div class="animate-fade-in">
    <!-- Header -->
    <div class="mb-8 animate-slide-in-left">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('books.index') }}" class="p-2 hover:bg-light-gray rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Editar Libro</h1>
                <p class="text-text-secondary mt-1">Actualiza la información del libro</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm p-8 max-w-3xl animate-scale-in">
        <form action="{{ route('books.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título del Libro <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $book->title) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('title') border-red-500 @enderror"
                        required
                    >
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Autor -->
                <div class="md:col-span-2">
                    <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                        Autor <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="author"
                        id="author"
                        value="{{ old('author', $book->author) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('author') border-red-500 @enderror"
                        required
                    >
                    @error('author')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ISBN -->
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                        ISBN <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="isbn"
                        id="isbn"
                        value="{{ old('isbn', $book->isbn) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('isbn') border-red-500 @enderror"
                        required
                    >
                    @error('isbn')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Año de Publicación -->
                <div>
                    <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-2">
                        Año de Publicación <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="publication_year"
                        id="publication_year"
                        value="{{ old('publication_year', $book->publication_year) }}"
                        min="1000"
                        max="{{ date('Y') }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('publication_year') border-red-500 @enderror"
                        required
                    >
                    @error('publication_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría
                    </label>
                    <select
                        name="category_id"
                        id="category_id"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Copias Disponibles -->
                <div>
                    <label for="copies_available" class="block text-sm font-medium text-gray-700 mb-2">
                        Copias Disponibles <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="copies_available"
                        id="copies_available"
                        value="{{ old('copies_available', $book->copies_available) }}"
                        min="0"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('copies_available') border-red-500 @enderror"
                        required
                    >
                    @error('copies_available')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('status') border-red-500 @enderror"
                        required
                    >
                        <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="unavailable" {{ old('status', $book->status) == 'unavailable' ? 'selected' : '' }}>No Disponible</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                <button
                    type="submit"
                    class="bg-accent hover:bg-opacity-90 text-white px-6 py-3 rounded-lg font-medium transition shadow-md"
                >
                    Actualizar Libro
                </button>
                <a
                    href="{{ route('books.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
