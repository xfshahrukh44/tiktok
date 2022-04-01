<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::paginate(10);

        $response = [
            'pagination' => [
                'total' => $videos->total(),
                'per_page' => $videos->perPage(),
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'from' => $videos->firstItem(),
                'to' => $videos->lastItem()
            ],
            'data' => $videos
        ];

        return response()->json($response);
    }

    public function my_videos()
    {
        $videos = Video::where('user_id', auth()->user()->id)->paginate(10);

        $response = [
            'pagination' => [
                'total' => $videos->total(),
                'per_page' => $videos->perPage(),
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'from' => $videos->firstItem(),
                'to' => $videos->lastItem()
            ],
            'data' => $videos
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'tags' => 'sometimes',
            'description' => 'required',
            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

        if($request->has('tags')){
            $req['tags'] = json_encode($request->tags);
        }
        $req['user_id'] = auth()->user()->id;

        $video = Video::create($req);

        return response()->json($video);
    }

    public function show($id)
    {
        if(!$video = Video::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json($video);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes',
            'category_id' => 'sometimes',
            'tags' => 'sometimes',
            'description' => 'sometimes',
            'link' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

        if($request->has('tags')){
            $req['tags'] = json_encode($request->tags);
        }

        $video = Video::find($id);
        if ($video->user_id != auth()->user()->id) {
            return response()->json([
                'error' => 'Not allowed'
            ]);
        }

        $video->update($req);
        return response()->json($video);
    }

    public function destroy($id)
    {
        if(!$video = Video::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        if ($video->user_id != auth()->user()->id) {
            return response()->json([
                'error' => 'Not allowed'
            ]);
        }

        return response()->json($video->delete());
    }

    public function feed(Request $request)
    {
        $query = $request->has('query') ? $request->query :  null;

        $videos = Video
            ::when($query, function($q) use($query) {
               return $q
                    ->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('tags', 'LIKE', '%'.$query.'%')
                    ->orWhereHas('category', function($q) use ($query) {
                        return $q->where('name', 'LIKE', '%'.$query.'%');
                    });
            })
            ->paginate(10);

        $response = [
            'pagination' => [
                'total' => $videos->total(),
                'per_page' => $videos->perPage(),
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'from' => $videos->firstItem(),
                'to' => $videos->lastItem()
            ],
            'data' => $videos,
            'query' => $query,
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

    public function videos_by_user(Request $request)
    {
        $videos = Video::where('user_id', $request->user_id)->paginate(10);

        $response = [
            'pagination' => [
                'total' => $videos->total(),
                'per_page' => $videos->perPage(),
                'current_page' => $videos->currentPage(),
                'last_page' => $videos->lastPage(),
                'from' => $videos->firstItem(),
                'to' => $videos->lastItem()
            ],
            'data' => $videos
        ];

        return response()->json($response);
    }
}
