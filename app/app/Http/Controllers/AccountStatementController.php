<?php

namespace App\Http\Controllers;

use App\Models\AccountStatement;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountStatementController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'numeric',
            'account_id' => 'required|exists:account,id'
        ]);
 
        $request_data = $request->all();

        $account = Account::find($request_data['account_id']);

        $account_balance = $account->balance;
        $value = $request_data['value'];

        $account_balance+=$value;

        if($value<0 && $account_balance<0){
            return response()->json(['message'=>"Insufficient account balance!"], 422);
        }

        $account->balance = $account_balance;

        DB::beginTransaction();

        try{
            $account->update($account->toArray());

            $transaction = AccountStatement::create($request_data);

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>"An error occurred while executing the account transaction!"], 500);
        }

        return [
            "message" => "Transactions successfully created!"
        ];
    }
}
