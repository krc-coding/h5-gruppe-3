<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccessTokenResource;
use App\Http\Resources\UserResource;
use App\Models\AccessToken;
use App\Models\Token;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // this is a help function not an api
    private function createToken(User $user)
    {
        $token = AccessToken::create([
            'user_id' => $user->id,
            'token' => Str::uuid() . '-' . Str::uuid(),
            'expire_at' => now()->add(1, 'day'),
        ]);

        return new AccessTokenResource($token);
    }

    public function getUser(User $user)
    {
        return new UserResource($user);
    }

    public function getAllUsers()
    {
        return User::all()->mapInto(UserResource::class);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            // With the 'confirmed' tag the frontend needs to send two identically passwords.
            // 'Password' is used to set password standarts on our webside, 
            // it can do more then set a minimum, but we didn't see a reason to add more rules.
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Here we return a user resource and a token created from our help function
        return response()->json(['user' => new UserResource($user), 'token' => UserController::createToken($user)->token], 201);
    }

    public function putUser(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save(); // Save to database

        return new UserResource($user);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) { // 'attempt' is making a error, but it works
            return response(['error_message' => 'Incorrect username or password.'], 401);
        }

        $user = User::where('username', $data["username"])->first();
        $tokens = $user->accessTokens();
        $tokens->delete(); // Logout all other devices that is using this user

        return response()->json(['user' => new UserResource($user), 'token' => UserController::createToken($user)->token], 201);
    }

    public function logout(User $user)
    {
        $user->accessTokens()->delete();
        return response()->json([], 204);
    }

    public function delete(User $user)
    {
        $user->delete();
        return response()->json([], 204);
    }
}
