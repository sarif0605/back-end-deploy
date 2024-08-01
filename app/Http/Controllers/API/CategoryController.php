<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryCollections;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryResourceById;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', only: ['store', 'update', 'destroy']),
            new Middleware(middleware:'isOwner' ,only: ['store', 'update', 'destroy'])
        ];
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 1);
        $categories = Categories::orderBy('created_at', 'desc')->paginate(6, ['*'], 'page', $perPage);
        $categoriyResponse = CategoryResource::collection($categories);
        return (new CategoryCollections($categoriyResponse))->response()->setStatusCode(201);
    }

    public function getAll()
    {
        $categories = Categories::all();
        return response()->json([
            'message' => 'Success',
            'data' => CategoryResource::collection($categories)
        ], 200);
    }

    public function store(CategoryCreateRequest $request)
    {
        $validateCategory = $request->validated();
        $category = new Categories($validateCategory);
        $category->save();
        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::with('list_books')->find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category with ID $id not found"
            ], 404);
        }
        return (new CategoryResourceById($category))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category with ID $id not found"
            ], 404);
        }
        $validateCategory = $request->validated();
        $category->update($validateCategory);
        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category with ID $id not found"
            ], 404);
        }
        $category->delete();
        return response()->json([
            "message" => "Category with ID $id deleted"
        ], 201);
    }
}
