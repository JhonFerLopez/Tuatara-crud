<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index()
    {    	
        $users = User::all();
        return response()->json($users, 200);
    }
    public function indexFilter($id)
    {
        $users = User::where(function ($query) use ($id) {
            $query->where('firstname', 'like', '%'.$id.'%')
                ->orWhere('lastname', 'like', '%'.$id.'%')
                ->orWhere('phone', 'like', '%'.$id.'%')
                ->orWhere('email', 'like', '%'.$id.'%');
        })->get();
        return response()->json($users, 200);
    }

    public function showOneUser($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
    	$this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $User = User::create($request->all());

        return response()->json($User, 201);
    }

    public function update($id, Request $request)
    {
        $User = User::findOrFail($id);
        $User->update($request->all());

        return response()->json($User, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}