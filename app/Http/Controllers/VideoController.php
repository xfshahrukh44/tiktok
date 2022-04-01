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
        $title = $request->has('title') ? $request->title :  null;
        $category = $request->has('category') ? $request->category :  null;
        $tag = $request->has('tag') ? $request->tag :  null;

        $videos = Video
            ::when($title, function($q) use($title) {
               return $q->where('title', 'LIKE', '%'.$title.'%');
            })
            ->when($category, function($q) use($category) {
                return $q->whereHas('category', function($q) use ($category) {
                    return $q->where('name', 'LIKE', '%'.$category.'%');
                });
            })
            ->when($tag, function($q) use($tag) {
                return $q->whereIn('tags', $tag);
            })
            ->paginate(10);

        $search_data = [
            'title' => $title,
            'category' => $category,
            'tag' => $tag,
        ];

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
            'search_data' => $search_data,
        ];

        return response()->json($response);
    }
}
