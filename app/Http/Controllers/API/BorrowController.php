<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BorrowController extends Controller
{
    public function createOrUpdate(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'load_date' => 'required|date',
            'borrow_date' => 'required|date',
            'book_id' => 'required|exists:books,id', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = Auth::guard('api')->user();
        $bookId = $request->input('book_id');

        try {
            $book = Book::findOrFail($bookId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found.'], 404);
        }

  
        if ($book->stock <= 0) {
            return response()->json(['error' => 'Book out of stock.'], 400);
        }

        $borrow = Borrow::where('user_id', $currentUser->id)
                        ->where('book_id', $bookId)
                        ->first();

        if ($borrow) {
          
            $book->increment('stock');

           
            $borrow->delete();
            return response()->json([
                'message' => 'Borrow Berhasil Di Update , Buku Telah di kembalikan',
                'data' => $borrow
            ]);
        } else {
           
            $borrow = Borrow::create([
                'load_date' => $request->input('load_date'),
                'borrow_date' => $request->input('borrow_date'),
                'book_id' => $bookId,
                'user_id' => $currentUser->id,
            ]);

            
            $book->decrement('stock');
            return response()->json([
                'message' => 'Borrow Berhasil Di Buat , buku di pinjam',
                'data' => $borrow
            ]);
        }

        return response()->json([
            'message' => 'Borrow record created/updated successfully',
            'data' => $borrow
        ]);
    }

    public function index()
    {
        $borrows = Borrow::with(['user', 'book'])->get();

        return response()->json([
            'message' => 'All Borrowed Books',
            'data' => $borrows
        ]);
    }
}
