<?php

namespace Edwin\Calendar\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController
{
    public function index(Request $request)
    {
        $users = User::all();
        return $users;
    }

    public function store(Request $request)
    {
        $this->validate($request);

        $user = User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
        ]);

        return $user;
    }

    public function destroy(User $user)
    {
        User::destroy($user->id);
        return response()->json([
            'ok' => true,
            'message' => 'user with id' . $user->id . 'deleted',
        ]);
    }

    private function validate(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|min:5|max:255|email',
            'password' => 'required|min:5|max:255',
        ]);
    }
}
