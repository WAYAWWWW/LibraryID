<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(15);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $isAdmin = auth()->user()->isAdmin();
        return view('books.create', compact('isAdmin'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'pages' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1|max:999',
            'description' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('books', $imageName, 'public');
            $data['cover_image'] = 'books/' . $imageName;
        }

        // Map quantity to stock
        $data['stock'] = $data['quantity'];
        unset($data['quantity']);

        $data['reads_count'] = 0;
        $data['synopsis'] = $data['description'] ?? '';
        $data['year'] = $data['publication_year'];

        Book::create($data);
        return redirect()->route('petugas.dashboard')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Book $book)
    {
        $reviews = $book->reviews()->with('user')->get();
        
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role === 'petugas')) {
            return view('books.show', compact('book', 'reviews'));
        }
        
        return view('books.detail', compact('book', 'reviews'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'publication_year' => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
            'cover_image_url' => 'nullable|url',
        ]);

        // Handle cover image URL if provided
        if ($request->filled('cover_image_url')) {
            $data['cover_image'] = $data['cover_image_url'];
            unset($data['cover_image_url']);
        } else {
            unset($data['cover_image_url']);
        }

        // Set year field from publication_year
        if (isset($data['publication_year'])) {
            $data['year'] = $data['publication_year'];
        }

        $book->update($data);
        return redirect()->route('petugas.kelola-buku')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('petugas.kelola-buku')->with('success', 'Buku berhasil dihapus!');
    }

    public function manageBuku()
    {
        $isAdmin = auth()->user()->isAdmin();
        $books = Book::all();
        return view('books.manage', compact('books', 'isAdmin'));
    }

    public function apiShow(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Serve cover image for book from database
     * Route: /books/{id}/cover
     * Returns book cover image from storage or default placeholder
     */
    public function cover(Book $book)
    {
        // Check if book has cover image
        if (!$book->cover_image) {
            // Return default placeholder image
            return response()->file(public_path('images/Lbuku.png'));
        }

        // Get the full path to the image in storage
        $imagePath = storage_path('app/public/' . $book->cover_image);

        // Check if file exists in storage
        if (!file_exists($imagePath)) {
            // Return default placeholder image if file doesn't exist
            return response()->file(public_path('images/Lbuku.png'));
        }

        // Serve the image with appropriate headers
        return response()->file($imagePath, [
            'Content-Type' => mime_content_type($imagePath),
            'Cache-Control' => 'public, max-age=86400', // Cache for 24 hours
        ]);
    }
}
