<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado - BiblioTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#FD583B',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Acceso Denegado</h1>
        <p class="text-gray-600 mb-8">No tienes permisos para acceder a esta página.</p>
        <div class="space-y-3">
            <a href="{{ route('dashboard') }}" class="block w-full bg-accent hover:bg-opacity-90 text-white px-6 py-3 rounded-lg font-medium transition">
                Ir al Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                @csrf
                <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
