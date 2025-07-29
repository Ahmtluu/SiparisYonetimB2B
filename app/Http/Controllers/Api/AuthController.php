<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => ["required", "string"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required"],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        $token = $user->createToken("accessToken")->plainTextToken;

        return response()->json(["user" => $user, "accessToken" => $token], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required|string"
        ]);

        $user = User::where("email", $credentials["email"])->first();

        if (!$user || !Hash::check($credentials["password"], $user->password)) {
            return response()->json(["message" => "Invalid credentials"], 401);
        }

        $token = $user->createToken("accessToken")->plainTextToken;

        return response()->json(["user" => $user, "accessToken" => $token], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(["message" => "Logged out"]);
    }
}
