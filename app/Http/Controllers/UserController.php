<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|min:8|max:100',
        ]);

        try {
            // Find user by email
            $user = User::where('email', $validatedData['email'])->first();

            $verify = Hash::check($validatedData['password'], $user->password);
            $data = [
                'username'=>$user->username,
                'email'=>$user->email
            ];
            if($verify){
                $jwt = JWT::encode($data, env('JWT_KEY'), 'HS256');
                return response()->json(['message'=>'login success','token'=>$jwt],200);
            }
            return response()->json(['message' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response(['error' => $e->getMessage()]);
        }
    }

    public function register(Request $request){
        $validate = $request->validate([
            'username'=>'required|max:100',
            'email'=>'required|min:8|max:100|unique:users,email',
            'password'=>'required|max:50',
            'mobile'=>'required',
            'country'=>'required',
            'province'=>'required',
            'city'=>'required',
            'landmark'=>'required',
            'address'=>'required',
            'pincode'=>'required',
        ],[
            'email'=>[
                'required'=>'email id is required',
                'max'=>'email should be less than 100 characters',
                'unique'=>'email already exits'
            ]
        ]);
        if(@$validate->errors){
            return response(['validationError'=>$validate->errors]);
        }

        try{
            $validate['password'] = Hash::make($request->password);
            $user = User::create($validate);
            $data = [
                'username'=>$user->username,
                'email'=>$user->email,
                'mobile'=>$user->mobile,
            ];
            return response(['status'=>201,'message'=>'user created!','user'=>$data]);
        }catch (\Exception $e){
            return response(['error'=>$e->getMessage()]);
        }
    }
}
