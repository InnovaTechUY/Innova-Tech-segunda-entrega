<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



use \Illuminate\Database\QueryException;


class UserController extends Controller
{
    public function Create(Request $request){
        $validation = validateCreation($request);

        if($validation !== "true")
            return $validation;

        try {
            return createUser($request);
        }
        catch (QueryException $e){
            return handleCreateErrors($$e);
        }
    }

    public function Authenticate(Request $request){
        $validation = validateAuthentication($request);

        if($validation !== "true")
            return $validation;

        return doAuthentication($request->only('email', 'password'));
    }


    private function validateCreation($request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6'
        ]);

        if($validator -> fails())
            return $validator->errors()->toJson();
        
        if($request -> post("password") !== $request -> post("password_confirmation"))
            return [ "password" => "Both passwords don't match"];
        
        return "true";
    }

    private function createUser($request){
        return User::create([
            'name' => $request -> post("name"),
            'email' => $request -> post("email"),
            'password' => Hash::make($request -> post("password"))
        ]);
    }
    
    private function handleCreateErrors($e){
        return [
            "error" => 'User ' . $request->post("name") . ' exists',
            "trace" => $e -> getMessage()
        ];
    }


    private function validateAuthentication($request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) 
            return $validator->errors()->toJson();
        return "true";
    }

    private function doAuthentication($credentials){
        if(Auth::attempt($credentials))
            return [
                'Status' => true,
                'Result' => "Popotico"
            ];
    
        return [
            'Status' => false,
            'Result' => "No Popotico"
        ];
    }
}
