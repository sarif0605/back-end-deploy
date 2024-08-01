<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookCreateRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\BookResourceById;
use App\Models\Books;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class BookController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', only: ['store', 'update', 'destroy']),
            new Middleware(middleware:'isOwner' ,only: ['store', 'update', 'destroy'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $books = Books::orderBy('created_at', 'desc')->paginate(6, ['*'], 'page', $page);
        // $moviesRes = MovieResponse::collection($movies);
        return (new BookCollection($books))->response()->setStatusCode(201);
    }

    public function getAll(): JsonResponse
    {
        $book = Books::all();
        return response()->json([
            'message' => 'Success',
            'data' => BookResource::collection($book)
        ], 200);
    }

    public function bookNews(){
        $book = Books::orderBy('created_at', 'desc')->take(4)->get();
        return response()->json([
            'message' => 'Success',
            'data' => BookResource::collection($book)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookCreateRequest $request) : JsonResponse
    {
        try {
            $data = $request->validated();
            $cloudinaryImage = $request->file('image')->storeOnCloudinary('movies');
            $url = $cloudinaryImage->getSecurePath();
            $public_id = $cloudinaryImage->getPublicId();
            $data['image'] = $url;
            $data['public_image_id'] = $public_id;
            $book = Books::create($data);
            return (new BookResource($book))->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }    

    public function bookZero()
    {
        $books = Books::where('stock', 0)->get();
        return response()->json([
            'message' => 'Success',
            'data' => BookResource::collection($books)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bookId = Books::with('category','list_borrows')->find($id);
        if (!$bookId) {
            return response()->json([
                'message' => 'Book dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        return (new BookResourceById($bookId))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, string $id) : JsonResponse
    {
        try {
            $book = Books::find($id);
            if (!$book) {
                return response()->json([
                    'message' => 'Book dengan ID ' . $id . ' tidak ditemukan',
                ], 404);
            }
            
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($book->public_image_id) {
                    Cloudinary::destroy($book->public_image_id);
                }
                $cloudinaryImage = $request->file('image')->storeOnCloudinary('movies');
                $url = $cloudinaryImage->getSecurePath();
                $public_id = $cloudinaryImage->getPublicId();
                $data['image'] = $url;
                $data['public_image_id'] = $public_id;
            }
            $book->update($data);
            return (new BookResource($book))->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => 'Gagal memperbarui gambar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Books::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Movie dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        Cloudinary::destroy($book->public_image_id);
        $book->delete();
        return response()->json([
            'message' => 'Book berhasil dihapus',
        ], 200);
    }
    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 6);
        $searchQuery = $request->input('query');
        $books = Books::query();
        if ($searchQuery) {
            $books = $books->where(function (Builder $builder) use ($searchQuery) {
                $builder->where('title', 'like', '%' . $searchQuery . '%')
                        ->orWhere('summary', 'like', '%' . $searchQuery . '%')
                        ->orWhere('stock', 'like', '%' . $searchQuery . '%');
            });
        }

        $books = $books->paginate(perPage: $size, page: $page);
        return (new BookCollection($books))->response()->setStatusCode(201);
    }
}
