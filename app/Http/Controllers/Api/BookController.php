<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    #[OA\Get(
        path: '/api/books',
        summary: 'Daftar semua buku',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Daftar buku berhasil diambil'),
        ]
    )]
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Book::latest()->get(),
        ]);
    }

    #[OA\Post(
        path: '/api/books',
        summary: 'Tambah buku baru',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['isbn', 'title', 'author', 'year'],
                properties: [
                    new OA\Property(property: 'isbn', type: 'string', example: '978-623-1234-56-7'),
                    new OA\Property(property: 'title', type: 'string', example: 'Belajar Laravel 12'),
                    new OA\Property(property: 'author', type: 'string', example: 'Edwin Hap'),
                    new OA\Property(property: 'year', type: 'integer', example: 2026),
                    new OA\Property(property: 'stock', type: 'integer', example: 10),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Buku berhasil dibuat'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isbn'   => 'required|string|max:20',
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year'   => 'required|integer|min:1900|max:2100',
            'stock'  => 'nullable|integer|min:0',
        ]);

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dibuat',
            'data' => $book,
        ], 201);
    }

    #[OA\Get(
        path: '/api/books/{id}',
        summary: 'Detail satu buku',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detail buku'),
            new OA\Response(response: 404, description: 'Buku tidak ditemukan'),
        ]
    )]
    public function show(Book $book)
    {
        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }

    #[OA\Put(
        path: '/api/books/{id}',
        summary: 'Update buku',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'isbn', type: 'string'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'author', type: 'string'),
                    new OA\Property(property: 'year', type: 'integer'),
                    new OA\Property(property: 'stock', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Buku berhasil diupdate'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'isbn'   => 'sometimes|required|string|max:20',
            'title'  => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'year'   => 'sometimes|required|integer|min:1900|max:2100',
            'stock'  => 'nullable|integer|min:0',
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diupdate',
            'data' => $book,
        ]);
    }

    #[OA\Delete(
        path: '/api/books/{id}',
        summary: 'Hapus buku',
        tags: ['Books'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Buku berhasil dihapus'),
            new OA\Response(response: 404, description: 'Buku tidak ditemukan'),
        ]
    )]
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus',
        ]);
    }
}