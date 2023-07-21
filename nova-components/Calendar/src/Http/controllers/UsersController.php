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
        $request->validate(['password' => 'required|min:5|max:255']);

        $userExist = $this->verifyEmail($request->input('email'));

        if ($userExist !== null) {
            return $userExist;
        } else {
            $user = User::create([
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                "password" => Hash::make($request->input('password')),
            ]);

            return $user;
        }
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->input('password') !== null) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

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
        ]);
    }

    private function verifyEmail(String $email)
    {
        $user = User::whereEmail($email)->first();
        if ($user !== null) {
            return $user;
        }
    }
}
