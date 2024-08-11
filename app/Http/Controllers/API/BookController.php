<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use App\Models\Book;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    function __construct()
    {
        $this->middleware('check.admin')->only('store', 'update', 'destroy');
        $this->middleware('auth:api')->only('store', 'update', 'destroy');
    }

    public function index()
    {
        $books = Book::all();
        return response()->json([
            "Message" => "Tampilkan Semua book",
            "data" => $books,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('images')) {
            $uploadedFile = $request->file('images');
            $uploadResult = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'books/images',
            ]);
            
            $data['images'] = $uploadResult->getSecurePath();
        }

        $book = Book::create($data);

        return response()->json([
            "message" => "Input book Berhasil",
            'data' => $book
        ], 201);
    }

    public function show(string $id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json([
                "Message" => "Data book Dengan ID : $id Tidak ditemukan",
                "data" => $book
            ], 404);
        }

        return response()->json([
            "Message" => "Tampilkan Data book",
            "data" => $book,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $data = $request->validated();
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "message" => "Data book Dengan ID : $id Tidak ditemukan",
                "data" => null
            ], 404);
        }

        if ($request->hasFile('images')) {
            // Hapus gambar lama jika ada
            if ($book->images) {
                $publicId = $this->getPublicIdFromUrl($book->images);
                Cloudinary::destroy($publicId);
            }

            $uploadedFile = $request->file('images');
            $uploadResult = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'books/images',
            ]);

            $data['images'] = $uploadResult->getSecurePath();
        }

        $book->update($data);

        return response()->json([
            "message" => "Update Data book Berhasil",
            'data' => $book
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "Message" => "Data book Dengan ID : $id Tidak ditemukan",
                "data" => $book
            ], 404);
        }
        if ($book->images) {
            $publicId = $this->getPublicIdFromUrl($book->images);
            Cloudinary::destroy($publicId);
        }
        $book->delete();

        return response()->json([
            "message" => "Data book Dengan ID : $id Berhasil Di Delete",
            'data' => $book
        ], 200);
    }

    /**
     * Extract public ID from Cloudinary URL
     */
    private function getPublicIdFromUrl($url)
    {
        $parts = explode('/', parse_url($url, PHP_URL_PATH));
        return implode('/', array_slice($parts, -3, 2)); // Extracts public_id from URL
    }
}
