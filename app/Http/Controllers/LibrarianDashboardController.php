<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;

class LibrarianDashboardController extends Controller
{
    public function index()
    {
        //EStadisticas para el dashboard del bibliotecario
        $totalBooks = Book::count();
        $totalLoans = Loan::count();
        $overdueLoans = Loan::where('status', 'overdue')->count();

        //Prestamos
        $totalBooks = Book::count();
        $totalLoans = Loan::count();
        $overdueLoans = Loan::where('status', 'overdue')->count();
        $ongoingLoans = Loan::where('status', 'ongoing')->count();

        //Libros
        $totalBooks = Book::count();
        $availableBooks = Book::where('status', 'available')->count();
        $unvailableBooks = Book::where('status', 'unavailable')->count();
        

        $rolTransletion = [
            'librarian' => 'Bibliotecario',
            'admin' => 'Administrador',
            'member' => 'Miembro',
        ];

        $statusTransletion = [
            'ongoing' => 'En curso',
            'returned' => 'Devuelto',
            'overdue' => 'Atrasado',
            'unvailable' => 'No disponible',
            'available' => 'Disponible',
        ];
        return view('dashboard.librarian',[
            'totalBooks' => $totalBooks,
            'availableBooks' => $availableBooks,
            'unvailableBooks' => $unvailableBooks,
            'totalLoans' => $totalLoans,
            'ongoingLoans' => $ongoingLoans,
            'overdueLoans' => $overdueLoans,
            'rolTransletion' => $rolTransletion,
            'statusTransletion' => $statusTransletion,
        ]);


    }

    
}
