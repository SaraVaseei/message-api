<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param RegisterUserRequest $request
     * @return mixed
     */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        if ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);
            $token = $user->createToken('myAppToken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response()->success($response, 'User is registered successfully!', 201);
        } else {
            return response()->error(400, 'Data input validation has some errors.');
        }
    }

    /**
     * @param LoginUserRequest $request
     * @return mixed
     */
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        if ($validated) {
            // Check email
            $user = User::where('email', $validated['email'])->first();

            // Check password
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->error(401, 'The provided credentials are incorrect.');
            }


            $token = $user->createToken('myAppToken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response()->success($response, 'User has logged in successfully!', 201);
        } else {
            return response()->error(400, 'Data input validation has some errors.');
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->success([], 'User has logged out successfully!', 440);
    }
}
