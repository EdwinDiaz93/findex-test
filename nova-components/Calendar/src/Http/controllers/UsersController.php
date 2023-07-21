<?php

namespace Edwin\Calendar\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController
{
    public function index(Request $request)
    {
        $users = User::all();
        return $users;
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|min:5|max:255|email',
            'password' => 'required|min:5|max:255',
        ]);

        return $validate;
    }
}
