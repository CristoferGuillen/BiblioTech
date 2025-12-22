@extends('layouts.librarian')

@section('title', 'Editar Libro')

@section('content')
<div class="fade-in max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('books.index') }}" class="text-accent hover:underline flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Editar Libro</h1>
        <p class="text-text-secondary">Actualiza la información del libro</p>
    </div>

    <!-- Form -->
    <form action="{{ route('books.update', $book->id) }}" method="POST" class="bg-white rounded-xl shadow-sm p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Título del Libro <span class="text-accent">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $book->title) }}"
                    class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('title') border-red-500 @enderror" 
                    required
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author -->
            <div>
                <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">
                    Autor <span class="text-accent">*</span>
                </label>
                <input 
                    type="text" 
                    id="author" 
                    name="author" 
                    value="{{ old('author', $book->author) }}"
                    class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('author') border-red-500 @enderror" 
                    required
                >
                @error('author')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ISBN and Published Year -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="isbn" class="block text-sm font-semibold text-gray-700 mb-2">
                        ISBN <span class="text-accent">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="isbn" 
                        name="isbn" 
                        value="{{ old('isbn', $book->isbn) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('isbn') border-red-500 @enderror" 
                        maxlength="13"
                        required
                    >
                    @error('isbn')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_year" class="block text-sm font-semibold text-gray-700 mb-2">
                        Año de Publicación <span class="text-accent">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="published_year" 
                        name="published_year" 
                        value="{{ old('published_year', $book->published_year) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('published_year') border-red-500 @enderror" 
                        min="1000"
                        max="{{ date('Y') }}"
                        required
                    >
                    @error('published_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Category and Copies -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Categoría
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('category_id') border-red-500 @enderror"
                    >
                        <option value="">Sin categoría</option>
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

                <div>
                    <label for="copies_available" class="block text-sm font-semibold text-gray-700 mb-2">
                        Copias Disponibles <span class="text-accent">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="copies_available" 
                        name="copies_available" 
                        value="{{ old('copies_available', $book->copies_available) }}"
                        class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('copies_available') border-red-500 @enderror" 
                        min="0"
                        required
                    >
                    @error('copies_available')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Estado <span class="text-accent">*</span>
                </label>
                <select 
                    id="status" 
                    name="status"
                    class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('status') border-red-500 @enderror"
                    required
                >
                    <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="unavailable" {{ old('status', $book->status) == 'unavailable' ? 'selected' : '' }}>No disponible</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-light-gray">
            <button 
                type="submit" 
                class="bg-accent hover:bg-opacity-90 text-white px-8 py-3 rounded-lg font-medium transition shadow-md"
            >
                Actualizar Libro
            </button>
            <a 
                href="{{ route('books.index') }}" 
                class="bg-light-gray hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium transition"
            >
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
