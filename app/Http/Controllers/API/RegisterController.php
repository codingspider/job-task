<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends BaseController
{

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | min: 3',
            'email' => 'required|email|min:3|unique:users,email',
            'password' => 'required | min: 8',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        DB::beginTransaction();
        try {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyJobApp')->plainTextToken;
            $success['name'] =  $user;
            DB::commit();
            return $this->sendResponse($success, 'User register successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

     public function login(Request $request)
     {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyJobApp')->plainTextToken; 
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('User not found.', ['error'=>'Unauthorised']);
        } 
 
     }
}
