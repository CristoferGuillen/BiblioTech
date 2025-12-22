<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'book' => function ($query) {
            $query->withTrashed();
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        return view('loans.index', compact('loans'));
    }

    public function create ()
    {
       $users = User::all();
    $books = Book::where('copies_available', '>', 0)->get();
        return view('loans.create', compact('users', 'books'));
    }
    
    public function store (Request $request)
    {
         $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',

        ]);
        $book = Book::findOrFail($validatedData['book_id']);

        if ($book->copies_available <= 0) {
            return redirect()->back()->withErrors(['book_id' => 'No hay copias disponibles para este libro.'])
            ->withInput();
        }
        Loan::create(array_merge($validatedData, [
            'loan_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'ongoing',
        ]));
        $book->decrement('copies_available');

        return redirect()->route('loans.index')->with('success', 'Préstamo creado exitosamente.');
    }
    public function show ($id)
    {
        $loan = Loan::with(['user', 'book' => function ($query) {
            $query->withTrashed();
        }])
        ->findOrFail($id);
        return view('loans.show', compact('loan'));
    }

    public function return($id)
    {
        $loan = Loan::with(['book' => function ($q) {
            $q->withTrashed(); 
        }])->findOrFail($id);

        $loan->status = 'returned';
        $loan->return_date = now();
        $loan->save();

        if ($loan->book) {
            $loan->book->increment('copies_available');
        }

        return redirect()->route('loans.index')
            ->with('success', 'Libro devuelto exitosamente.');
    }

    public function renew($id)
    {
        $loan = Loan::with(['book' => function ($query) {
                $query->withTrashed();
            }])
            ->findOrFail($id);
        
        if (!$loan->book) {
            return redirect()->route('loans.index')
                ->with('error', 'No se puede renovar: el libro ha sido eliminado.');
        }
        
        $loan->due_date = $loan->due_date->addDays(7);
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Préstamo renovado exitosamente.');
    }



}
