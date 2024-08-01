<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Borrow\BorrowCreateRequest;
use App\Http\Requests\Borrow\BorrowUpdateRequest;
use App\Http\Resources\Borrow\BorrowCollection;
use App\Http\Resources\Borrow\BorrowResource;
use App\Models\Books;
use App\Models\Borrows;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 1);
        $borrows = Borrows::orderBy('created_at', 'desc')->paginate(6, ['*'], 'page', $perPage);
        $borrowResponse = BorrowResource::collection($borrows);
        return (new BorrowCollection($borrowResponse))->response()->setStatusCode(201);
    }
    public function store(BorrowCreateRequest $request)
    {
        $data = $request->validated();
        $currentUser = auth()->user();
        $book = Books::find($data['book_id']);
        if (!$book || $book->stock <= 0) {
            return response()->json([
                'message' => 'Book not available or out of stock.'
            ], 400);
        }
        $borrow = Borrows::updateOrCreate(
            ['user_id' => $currentUser->id, 'book_id' => $data['book_id']],
            [
                'load_date' => $data['load_date'],
                'borrow_date' => $data['borrow_date'],
                'user_id' => $currentUser->id,
            ]
        );
        $book->stock -= 1;
        $book->save();
        return (new BorrowResource($borrow))->response()->setStatusCode(201);
    }
}
