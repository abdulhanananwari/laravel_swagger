<?php

namespace App\Http\Controllers;
use App\Helpers\Controllers\ApiBaseController as Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Validator;
use JWTAuth;

class AuthController extends Controller {


  protected $user;

  public function __construct()
  {
    $this->user = new User();
    

  }
   /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Auth"},
     *   summary="JWT login",
     *   description="Login a user and generate JWT token",
     *   operationId="jwtLogin",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="email",
     *                   description="User email",
     *                   type="string",
     *                   example="admin@gmail.com"
     *               ),
     *               @OA\Property(
     *                   property="password",
     *                   description="User password",
     *                   type="string",
     *                   example="d3v6789012345"
     *               ),
     *           )
     *       )
     *   ),
     *  @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="access_token",
     *                         type="string",
     *                         description="JWT access token"
     *                     ),
     *                     @OA\Property(
     *                         property="token_type",
     *                         type="string",
     *                         description="Token type"
     *                     ),
     *                     @OA\Property(
     *                         property="expires_in",
     *                         type="integer",
     *                         description="Token expiration in miliseconds"
     *                         
     *                     ),
     *                     example={
     *                         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
     *                         "token_type": "bearer",
     *                         "expires_in": 3600
     *                     }
     *                 )
     *             )
     *         }
     *     ),
     *   @OA\Response(response="401",description="Unauthorized"),
     * )
     */

  public function login(Request $request)
  {
        $validator = \Validator::make($request->all(), [
          'email' => 'required',
          'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
        }

        if (!$token =JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->generateToken($token);
  }

  protected function generateToken($token){
      return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
      ]);
  }
}