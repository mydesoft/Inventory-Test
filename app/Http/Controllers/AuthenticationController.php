<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request){
    	$validator = $this->validateRegRequest();
    	if ($validator->fails()) {
    		return response()->json([
    			'status' => false,
    			'msg' => $validator->errors()->all()
    		], 406);
    	}

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    	]);

    	return response()->json([
    		'status' => true,
    		'data' => collect($user)->except(['password'])
    	], 201);

    }

    public function login(Request $request){
    	$validator = $this->validateLoginRequest();
    	if ($validator->fails()) {
    		return response()->json([
    			'status' => false,
    			'msg' => $validator->errors()->all()
    		], 406);
    	}

    	if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    		$token = Auth::user()->createToken(Auth::user()->name)->accessToken;
    		return response()->json([
    			'success' => true,
    			'msg' => 'Login successful',
    			'data' => Auth::user(),
    			'token' => $token,
    		]);
    	}
    	else{
    		return response()->json([
    			'success' => false,
    			'msg' => 'Invalid Login Details',
    		], 406);
    	}



    }


    public function logout(){
    	Auth::user()->token()->delete();
    	return response()->json([
    		'success' => true,
    		'msg' => 'Logged out successfully'
    	], 200);
    }

    public function validateRegRequest(){
    	return Validator::make(request()->all(), [
    		'name' => 'required',
    		'email' => 'required|email|unique:users',
    		'password' => 'required',
    	]);
    }

    public function validateLoginRequest(){
    	return Validator::make(request()->all(), [
    		'email' => 'required|email',
    		'password' => 'required',
    	]);
    }
}
