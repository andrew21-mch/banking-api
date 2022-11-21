<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'branch_code' => ['required', 'integer', 'max:255'],
            'create_account' => ['required', 'boolean'],

        ]);

        // if create account is true, make sure account number is unique
        if($request->create_account == true){
            $validator = Validator::make($request->all(), [
                'account_type' => ['required', 'string', 'max:255'],
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            try {

                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone = $request->phone;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->address = $request->address;
                $user->dob = $request->dob;
                $user->branch_code = $request->branch_code;
                $user->save();
    
                $user->assignRole('customer');    
    
                $branch = Branch::find($request->branch_code);
    
                $account = new Account;
                $account->account_num = $this->generateAccountNumber($branch, $user);
                $account->type = $request->account_type;
                $account->balance = 0;
                $account->user_id = $user->id;
    
                if(!$account->save()){
                    return response()->json('Error creating account', 500);
                }
    
                return $this->userResponse($user);
    
            } catch (\Error $e) {
                $user->delete();
                return response()->json(['message' => $e], 409);
            }

        }
        else{
            try{
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone = $request->phone;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->address = $request->address;
                $user->dob = $request->dob;
                $user->branch_code = $request->branch_code;
                $user->save();

                return response()->json($user, 201);
            }  catch (\Exception $e) {
                $user->delete();
                return response()->json(['message' => $e], 409);
            }

           


        }

        
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>['required', 'string', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if (Hash::check($request->password, $user->password)) {

            // create api token
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'token' => $token
            ], 200);
        } else {
            $response = "Password missmatch";
            return response($response, 422);
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function userResponse($user)
    {
        $user->load('roles:name');
        $user->branch = Branch::find($user->branch_code);
        return response()->json($user);
    }

    public function updateRole(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'role' => ['required', 'string', 'max:12'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $user = User::find($request->id);
            $user->syncRoles($request->role);
            return $this->userResponse($user);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => $e], 409);
        }
    }

    private function generateAccountNumber($branch, $user)
    {
        $accountNumber = $branch->branch_code . $user->id;
        return $accountNumber;
    }

}
