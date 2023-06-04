<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::with('person')->withCount('statements')->get();

        return [
            "message" => "List of accounts successfully retrieved!",
            "data" => $accounts
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|regex:/^[0-9]+$/|unique:account|max:10',
            'person_id' => 'required|exists:person,id'
        ]);
 
        $request_data = $request->all();
        $request_data["balance"] = 0;

        $person = Account::create($request_data);

        return [
            "message" => "Account successfully created!"
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);

        if(!$account){
            return response()->json(['message'=>"Account not found!"], 404);
        }

        return [
            "message" => "Account successfully retrieved!",
            "data" =>$account
        ];
    }

    /**
     * Display the statement of the specified account.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function get_statement($id)
    {
        $account = Account::with('statements')->find($id);

        if(!$account){
            return response()->json(['message'=>"Account not found!"], 404);
        }

        return [
            "message" => "Account statement successfully retrieved!",
            "data" =>$account
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        if(!$account){
            return response()->json(['message'=>"Account not found!"], 404);
        }

        $request->validate([
            'number' => 'required|regex:/^[0-9]+$/|max:10|unique:account,number,'.$id,
            'person_id' => 'required|exists:person,id'
        ]);
 
        $request_data = $request->all();

        $request_data["balance"] = $account->balance;

        $account->update($request_data);
 
        return [
            "data" => $account,
            "message" => "Account successfully updated!"
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::withCount('statements')->find($id);

        if(!$account){
            return response()->json(['message'=>"Account not found!"], 404);
        }

        if($account->statements_count > 0){
            return response()->json(['message'=>"The account couldn't be removed 'cause it already has a transaction!"], 422);   
        }

        $account->delete();

        return [
            "data" => $account,
            "message" => "Account successfully removed!"
        ];
    }
}
