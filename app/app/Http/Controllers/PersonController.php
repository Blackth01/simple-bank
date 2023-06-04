<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::all();
        return [
            "message" => "List of people successfully retrieved!",
            "data" => $people
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
        error_log($request->getContent());
        $request->validate([
            'name' => 'required|max:255|real_name',
            'cpf' => 'required|regex:/^[0-9]+$/|max:11|min:11|unique:person',
            'address' => 'required'
        ]);
 
        $person = Person::create($request->all());

        return [
            "message" => "Person successfully created!"
        ];
    }
 
    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);

        if(!$person){
            return response()->json(['message'=>"Person not found!"], 404);
        }

        return [
            "message" => "Person successfully retrieved!",
            "data" =>$person
        ];
    }

    /**
     * Display the accounts that the specified person owns.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function get_accounts($id)
    {
        $person = Person::with('accounts')->find($id);

        if(!$person){
            return response()->json(['message'=>"Person not found!"], 404);
        }

        return [
            "message" => "Accounts successfully retrieved!",
            "data" =>$person->accounts
        ];
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $person = Person::find($id);

        if(!$person){
            return response()->json(['message'=>"Person not found!"], 404);
        }

        $request->validate([
            'name' => 'required|max:255|real_name',
            'cpf' => 'required|regex:/^[0-9]+$/|max:11|unique:person,cpf,'.$id,
            'address' => 'required'
        ]);
 
        $person->update($request->all());
 
        return [
            "data" => $person,
            "message" => "Person successfully retrieved!"
        ];
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::find($id);

        if(!$person){
            return response()->json(['message'=>"Person not found!"], 404);
        }

        $person->delete();
        return [
            "data" => $person,
            "message" => "Person successfully removed!"
        ];
    }
}
