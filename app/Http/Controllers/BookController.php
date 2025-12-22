<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;


class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(15);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books',
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
            'copies_available' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $book = Book::create($validatedData);
        return redirect()->route('books.index')->with('success', 'Libro creado exitosamente.');
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('books.edit', compact('book'));

    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $id,
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
            'copies_available' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
        ]);

        $book->update($validatedData);
        return redirect()->route('books.index')->with('success', 'Libro actualizado exitosamente.');
    }

    public function updateStatus(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:available,unavailable',
        ]);

        $book->update(['status' => $validatedData['status']]);
        return redirect()->route('books.index')->with('success', 'Estado del libro actualizado exitosamente.');
    }


    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Libro eliminado exitosamente.');
    }

    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $book->restore();
        return redirect()->route('books.index')->with('success', 'Libro restaurado exitosamente.');
    }
}
