<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $validate = $request->validate([
            'username'=>'required|max:100',
            'email'=>'required|max:100|unique:users,email',
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
