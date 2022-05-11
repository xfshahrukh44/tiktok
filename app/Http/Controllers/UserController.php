<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|unique:users',
            'handle' => 'nullable|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

//        social login check
        if($request->has('is_social') && $request->is_social == True) {
            $social_user_check = User::where('email', $request->email)->get();
//            if user exists
            if(count($social_user_check) > 0){
                $user = $social_user_check[0];
                if($user['social_login_type'] == $request->social_login_type){
                    auth()->login($user);
                    $token = JWTAuth::fromUser($user);
                    return $this->respondWithToken($token);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email has already been taken or associated with another account.'
                    ]);
                }
            }
        }

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'is_social' => $request['is_social'],
            'social_login_type' => $request['social_login_type'],
            'email' => $request['email'],
            'handle' => $request['handle'],
            'dob' => $request['dob'],
            'userName' => $request['userName'],
            'password' => Hash::make($request['password']),
        ]);

//        return response()->json($user);
        return $this->login($request);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];

        if (!$token = auth()->attempt($credentials)) {
//            dd('here');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $user = User::find(auth()->user()->id)->toArray();
        $ms = Carbon::now()->addSeconds(86400)->timestamp;
        $res = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'expiry' => Carbon::now()->addSeconds(86400)
        ];
//        dd($res);

        return response()->json(array_merge($user, $res));
    }

    public function index(Request $request)
    {
        $handle = $request->has('query') ? $request->get('query') : NULL;

        $users = User::when($handle, function($q) use ($handle) {
            return $q->where('handle', 'LIKE', '%'.$handle.'%');
        })
        ->paginate(10);

        $response = [
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ],
            'data' => $users
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'handle' => 'sometimes|unique:users',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

        if($request->has('password')){
            $req['password'] = Hash::make($request->password);
        }

        $user = User::create($req);
//        return $user;
        return response()->json($user);
    }

    public function show($id)
    {
        if(!$user = User::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,
            'handle' => 'sometimes|unique:users,' . $id,
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'password' => 'sometimes|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $req = $request->all();

        if($request->has('password')){
            $req['password'] = Hash::make($request->password);
        }

        $user = User::find($id);
        $user->update($req);
        return response()->json($user);
    }

    public function destroy($id)
    {
        if($id == auth()->user()->id){
            return response()->json(['success' => false, 'message' => 'Not allowed']);
        }

        if(!$user = User::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json($user->delete());
    }
}
