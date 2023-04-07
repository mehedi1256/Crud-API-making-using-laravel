<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //for registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|min:2|max:100',
            'email' =>'required|string|email|max:100|unique:users',
            'phone' =>'required|string|max:11|unique:users',
            'password' =>'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
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
            'phone' =>'required|string',
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

    public function store(Request $request)
    {
        $data = new Campaign;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->budget_amount = $request->budget_amount;
        $data->max_limit = $request->max_limit;
        $data->status = $request->status;
        $data->created_by = $request->created_by;
        $data->instock = $request->instock;
        $data->order_qty = $request->order_qty;

        $data->save();

        return response()->json([
            'message' => 'Campaign Created Successfully',
            'status' => 'Successful',
            'data' => $data
        ]);


        
    }

    //show campaign data

    public function showCampaignData()
    {

        return response()->json([
            // 'campaigns' => Campaign::get()
            Campaign::all('id', 'title', 'description', 'budget_amount')
        ]);
    }

    

    //for update campaign

    public function updateCampaign(Request $request, Campaign $user)
    {
        $user->title = $request->title;
        $user->description = $request->description;
        $user->budget_amount = $request->budget_amount;
        $user->max_limit = $request->max_limit;

        $user->save();

        return response()->json([
            'message' => 'Campaign Updated Successfully',
            'status' => 'Successful',
            'data' => $user
        ]);
    }

    //update campaign status
    public function updateCampaignStatus(Request $request, Campaign $user)
    {
        $user->status = $request->status;

        $user->save();

        return response()->json([
            'message' => 'Campaign Status  Updated Successfully',
            'status' => 'Successful',
            'data' => $user
        ]);
    }


    //delete campaign data
    public function deleteCampaign(Request $request, Campaign $user)
    {
        $user->delete();

        return response()->json([
           'message' => 'Campaign Deleted Successfully',
           'status' => 'Successful',
            'data' => $user
        ]);
    }

    //for show particular data
    public function show(User $id)
    {
        return response()->json(['id'=>$id]);
    }

    //show campaign details for join particular user
    public function campaignDetails($user)
    {
        // return response()->json(['user'=>$user]);
        // Table::select('title','description','budget_amount','instock','order_qty','created_by')->where('id', $user)->get();

        $data = DB::table('campaigns')
            ->select('title','description','budget_amount','instock','order_qty','created_by')
            ->where('id', $user)
            ->get();

            return response()->json($data);
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
