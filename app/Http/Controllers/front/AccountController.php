<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function register (Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'customer';
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'You have registered successfully',
        ], 200);
    }


    public function authenticate (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ], 400);

        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = User::find(Auth::user() -> id);

            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'token' => $token,
                'id' => $user->id,
                'name' => $user->name,
            ], 200);

        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Either email/password is incorrect'
            ], 401);

        }
    }

    public function getOrderDetails ($id, Request $request)
    {
        $order = Order::where([
                        'user_id' => $request->user()->id, 
                        'id' => $id
                        ])
                        ->with('items', 'items.product')
                        ->first();
        
        if ($order == null) {

            return response()->json([
                
                'status' => 404,
                'message' => 'Order not found',
                'data' => [],
                
            ], 404);
        } else {

            return response()->json([
                'status' => 200,
                'data' => $order,   
            ], 200);
        }
    }

    public function getOrders (Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)->get();

        return response()->json([
            'status' => 200,
            'data' => $orders,
        ], 200);
    }

    public function updateProfile (Request $request)
    {
        $user = User::find($request->user()->id);

        if ($user == null) {
            
            return response()->json([
                'status' => 404,
                'message' => 'User not found',
                'data' => [],
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->user()->id.',id',
            'address' => 'required|max:200',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip' => 'required|max:100',
            'mobile' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);


    }


}
