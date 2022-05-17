<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('user')->paginate(10);

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
            'video' => 'required|mimes:mp4,webm,mpg,mp2,mpeg,mpe,mpv,ogg,m4p,m4v,avi,wmv,mov,qt,flv,swf,avchd',
            'thumbnail' => 'required|mimes:jpg,jpeg,png',
//            'link' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

//        video
        $link = Storage::disk('s3')->put('videos', $request->video);
        $link = Storage::disk('s3')->url($link);
        $req['link'] = $link;

//        thumbnail
        if($request->has('thumbnail')){
            $thumbnail = Storage::disk('s3')->put('videos', $request->thumbnail);
            $thumbnail = Storage::disk('s3')->url($thumbnail);
            $req['thumbnail'] = $thumbnail;
        }

        if($request->has('tags')){
            $req['tags'] = json_encode($request->tags);
        }
        $req['user_id'] = auth()->user()->id;

        $video = Video::create($req);

        return response()->json($video);
    }

    public function show($id)
    {
        if(!$video = Video::with('user')->find($id)) {
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
            'thumbnail' => 'sometimes|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

//        thumbnail
        if($request->has('thumbnail')){
            $thumbnail = Storage::disk('s3')->put('videos', $request->thumbnail);
            $thumbnail = Storage::disk('s3')->url($thumbnail);
            $req['thumbnail'] = $thumbnail;
        }

        if($request->has('tags')){
            $req['tags'] = json_encode($request->tags);
        }

        if (!$video = Video::find($id)) {
            return response()->json([
                'error' => 'Not found.'
            ]);
        }
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
//        dd($request['query']);
        $query = $request->has('query') ? $request['query'] :  null;
        $category_id = $request->has('category_id') ? $request['category_id'] :  null;

        $videos = Video
            ::with('user')
            ->when($query, function($q) use($query) {
               return $q
                    ->where('title', 'LIKE', '%'.$query.'%')
                    ->orWhere('tags', 'LIKE', '%'.$query.'%')
                    ->orWhereHas('category', function($q) use ($query) {
                        return $q->where('name', 'LIKE', '%'.$query.'%');
                    });
            })
            ->when($category_id, function ($q) use ($category_id) {
                return $q->where('category_id', $category_id);
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
        $categories = Video::with('user')->where('category_id', $id)->paginate(10);

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

    public function videos_by_user(Request $request, $id)
    {
        $videos = Video::where('user_id', $id)->paginate(10);

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
