<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $borrows = Borrow::with(['book', 'user'])->get();
        $borrows->each(function ($borrow) {
            $borrow->is_overdue = $borrow->isOverdue();
        });
        return response()->json([
            'message' => 'Tampil data berhasil',
            'data' => $borrows
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'load_date' => 'required|date_format:Y-m-d H:i:s',
            'barrow_date' => 'required|date_format:Y-m-d H:i:s',
            'book_id' => 'required|uuid|exists:books,id',
            'return_date' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $user = Auth::user();
        $validatedData['user_id'] = $user->id;

        // Jika ada return_date, pastikan menggunakan zona waktu yang benar
        if (isset($validatedData['return_date'])) {
            $validatedData['return_date'] = Carbon::parse($validatedData['return_date'])->setTimezone('Asia/Jakarta');
        }

        $borrow = Borrow::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $validatedData['book_id']],
            $validatedData
        );

        // Update book stock
        $book = Book::findOrFail($validatedData['book_id']);
        if ($borrow->wasRecentlyCreated) {
            $book->decrement('stok');
        } elseif ($request->has('return_date') && $borrow->wasChanged('return_date')) {
            $book->increment('stok');
        }

        $borrow->load('book', 'user');
        $borrow->is_overdue = $borrow->isOverdue();

        return response()->json([
            'message' => 'Peminjaman berhasil dibuat/diubah',
            'data' => $borrow
        ], 201);
    }
}
