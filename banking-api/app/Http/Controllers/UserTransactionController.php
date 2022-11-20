<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class UserTransactionController extends Controller
{
    // this class will handle all transactions for a user, such as deposit, withdraw, transfer, etc

    public function deposit(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'max:255'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $account = Account::where('account_num', $request->account_number)->first();
        $user = User::find($request->user_id);

        $validation = $this->Validation($account, $user);
        
        if($validation != null){
            return $validation;
        }
        
        try {

            $account->balance += $request->amount;
            if(!$account->save()){
                return response()->json('Error updating account balance', 500);
            }
            $transaction = new Transaction;
            $transaction->account_num = $request->account_number;
            $transaction->account_id = $account->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = $user->branch_code;
            $transaction->transaction_type = 'deposit';
            $transaction->save();

            return response()->json($transaction);
        } catch (\Exception $th) {
            return response()->json($th);
        }
    }
    

    public function withdraw(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'max:255'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $account = Account::where('account_num', $request->account_number)->first();
            $user = User::find($request->user_id);

            $validation = $this->Validation($account, $user);
        
            if($validation != null){
                return $validation;
            }
            if($account->balance < $request->amount){
                return response()->json('Insufficient funds', 400);
            }

            $account->balance -= $request->amount;
            if(!$account->save()){
                return response()->json('Error updating account balance', 500);
            }
            $transaction = new Transaction;
            $transaction->account_num = $request->account_number;
            $transaction->account_id = $account->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = $user->branch_code;
            $transaction->transaction_type = 'withdraw';
            $transaction->save();

            return response()->json($transaction);
        } catch (\Exception $th) {
            return response()->json($th);
        }
    }

    public function transfer(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'max:255'],
            'destination_account_number' => ['required', 'string', 'max:255'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $account = Account::where('account_num', $request->account_number)->first();
            $destination_account = Account::where('account_num', $request->destination_account_number)->first();
            $user = User::find($request->user_id);

            
            
            $validation = $this->Validation($account, $user);
        
            if($validation != null){
                return $validation;
            }


            if(!$destination_account){
                return response()->json('Destination account does not exist', 400);
            }

            if($account->balance < $request->amount){
                return response()->json('Insufficient funds', 400);
            }

            $account->balance -= $request->amount;
            if(!$account->save()){
                return response()->json('Error updating account balance', 500);
            }
            $destination_account->balance += $request->amount;
            if(!$destination_account->save()){
                return response()->json('Error updating destination account balance', 500);
            }
            $transaction = new Transaction;
            $transaction->account_num = $request->account_number;
            $transaction->account_id = $account->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = $user->branch_code;
            $transaction->transaction_type = 'transfer';
            $transaction->save();

            return response()->json($transaction);
        } catch (\Exception $th) {
            return response()->json($th);
        }
    }

    public function statement($id)
    {
        try{
            $user = User::find($id);
            if(!$user){
                return response()->json('User does not exist', 400);
            }
            if(auth()->user()->id != $user->id && auth()->user()->role != 'admin'){
                return response()->json('Unauthorized', 401);
            }
            $account = Account::where('user_id', $user->id)->first();
            if(!$account){
                return response()->json('User does not have an account', 400);
            }
            $transactions = Transaction::where('account_id', $account->id)->get();
            return response()->json($transactions);
        }
        catch(\Exception $th){
            return response()->json($th);
        }
    }

    public function getTransaction(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'account_number' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'integer', 'max:255'],
            'transaction_id' => ['required', 'integer', 'max:255'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $account = Account::where('account_num', $request->account_number)->first();
            $user = User::find($request->user_id);

           $this->validateAccount($account, $user);

            $transaction = Transaction::where('account_num', $request->account_number)->where('id', $request->transaction_id)->first();

            return response()->json($transaction);
        } catch (\Exception $th) {
            return response()->json($th);
        }
    }

    public function Validation($account, $user)
    {
        if(!$user){
            return response()->json('User does not exist', 400);
        }
        if(!$account){
            return response()->json('Account does not exist', 400);
        }
        if($account->user_id != $user->id){
            return response()->json('Account does not belong to user', 400);
        }

        if($user->id != auth()->user()->id){
            return response()->json('User is not authorized to perform this transaction', 400);
        }

        if($account->status != 'active'){
            return response()->json('Account is not active', 400);
        }


    }

        
}
