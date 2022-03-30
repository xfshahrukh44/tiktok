<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);

        $response = [
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem()
            ],
            'data' => $categories
        ];

        return response()->json($response);
    }

    public function category_videos($id)
    {
        $categories = Video::where('category_id', $id)->paginate(10);

        $response = [
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem()
            ],
            'data' => $categories
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $category = Category::create($request->all());

        return response()->json($category);
    }

    public function show($id)
    {
        if(!$category = Category::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $category = Category::find($id);

        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        if(!$category = Category::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json($category->delete());
    }
}
