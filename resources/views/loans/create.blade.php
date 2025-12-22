@extends('layouts.librarian')

@section('title', 'Nuevo Préstamo')

@section('content')
<div class="fade-in max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('loans.index') }}" class="text-accent hover:underline flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a la lista
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Registrar Nuevo Préstamo</h1>
        <p class="text-text-secondary">Completa la información del préstamo</p>
    </div>

    <!-- Info Alert -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-blue-700 font-medium">Información del préstamo</p>
                <p class="text-blue-600 text-sm mt-1">
                    • El período de préstamo es de <strong>14 días</strong><br>
                    • Solo se muestran libros con copias disponibles<br>
                    • El usuario recibirá una notificación del préstamo
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('loans.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-8">
        @csrf

        <div class="space-y-6">
            <!-- User Selection -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Usuario <span class="text-accent">*</span>
                </label>
                <select 
                    id="user_id" 
                    name="user_id"
                    class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('user_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Selecciona un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-text-secondary text-sm mt-2">Selecciona el usuario que solicitó el préstamo</p>
            </div>

            <!-- Book Selection -->
            <div>
                <label for="book_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Libro <span class="text-accent">*</span>
                </label>
                <select 
                    id="book_id" 
                    name="book_id"
                    class="w-full px-4 py-3 border border-light-gray rounded-lg focus:outline-none focus:ring-2 focus:ring-accent @error('book_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Selecciona un libro</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} - {{ $book->author }} ({{ $book->copies_available }} disponibles)
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-text-secondary text-sm mt-2">Solo se muestran libros con copias disponibles</p>
            </div>

            <!-- Loan Info (Read-only) -->
            <div class="bg-light-gray rounded-lg p-6 space-y-4">
                <h3 class="font-semibold text-gray-800 mb-4">Información del Préstamo</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-text-secondary mb-1">Fecha de Préstamo</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-semibold text-gray-800">{{ now()->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-text-secondary mb-1">Fecha de Devolución</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="font-semibold text-gray-800">{{ now()->addDays(14)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-300">
                    <div class="flex items-center gap-2 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium">Duración del préstamo: <strong>14 días</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-4 mt-8 pt-6 border-t border-light-gray">
            <button 
                type="submit" 
                class="bg-accent hover:bg-opacity-90 text-white px-8 py-3 rounded-lg font-medium transition shadow-md flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Registrar Préstamo
            </button>
            <a 
                href="{{ route('loans.index') }}" 
                class="bg-light-gray hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium transition"
            >
                Cancelar
            </a>
        </div>
    </form>

    <!-- Quick Stats -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ count($users) }}</p>
                    <p class="text-sm text-text-secondary">Usuarios activos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ count($books) }}</p>
                    <p class="text-sm text-text-secondary">Libros disponibles</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent bg-opacity-10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">14</p>
                    <p class="text-sm text-text-secondary">Días de préstamo</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview selection
    const userSelect = document.getElementById('user_id');
    const bookSelect = document.getElementById('book_id');

    userSelect.addEventListener('change', function() {
        if(this.value) {
            this.classList.add('border-green-500');
        } else {
            this.classList.remove('border-green-500');
        }
    });

    bookSelect.addEventListener('change', function() {
        if(this.value) {
            this.classList.add('border-green-500');
        } else {
            this.classList.remove('border-green-500');
        }
    });
</script>
@endsection
