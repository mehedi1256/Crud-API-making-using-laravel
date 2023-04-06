<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //for registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|min:2|max:100',
            'email' =>'required|string|email|max:100|unique:users',
            'password' =>'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User registration Successfully',
            'user' => $user
        ]);
    }

    //for login

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' =>'required|string|email',
            'password' =>'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        if(!$token = auth()->attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized']);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    //for profile
    public function profile()
    {
        return response()->json(auth()->user());
    }

    //for token refresh
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    //for logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout Successfully']);
    }


    //for show data
    public function index()
    {
        return response()->json([
            'users' => User::get()
        ]);
    }

    //for store data

    /* public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => 'Created Successfully',
            'status' => 'Successful',
            'date' => $user
        ]);
    } */


    //for show particular data
    public function show(User $id)
    {
        return response()->json(['id'=>$id]);
    }

    //for update particular data

    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => 'Updated Successfully',
            'status' => 'Successful',
            'data' => $user
        ]);
    }

    //for delete particular data
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return response()->json([
           'message' => 'Deleted Successfully',
           'status' => 'Successful',
            'data' => $user
        ]);
    }
}
