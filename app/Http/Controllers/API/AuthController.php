<?php

namespace App\Http\Controllers\API;

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
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="registerUser",
     *      tags={"user"},
     *      summary="Register a new user",
     *      description="Returns new user data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name", "email", "password", "password_confirmation"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Name"
     *                  ),@OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Email"
     *                  ),@OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Password"
     *                  ),@OA\Property(
     *                      property="password_confirmation",
     *                      type="string",
     *                      description="Password confirmation"
     *                  ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
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
    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"user"},
     *      summary="User login",
     *      description="Returns user data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"email", "password"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Email"
     *                  ),@OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Password"
     *                  ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
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
    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logoutUser",
     *      tags={"user"},
     *      security={{"bearerAuth":{}}},
     *      summary="User logout",
     *      description="Returns new user data",
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={},
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->success([], 'User has logged out successfully!', 440);
    }
}
