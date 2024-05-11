<?php
use OpenApi\Annotations as OA;
/**
 * @OA\Post(
 *      path="/auth/login",
 *      tags={"auth"},
 *      summary="Login",
 *      @OA\Response(
 *           response=200,
 *           description="User data and JWT token"
 *      ),
 *      @OA\RequestBody(
 *          description="User credentials",
 *          @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", required=true, type="string", example="test@test.test"),
 *             @OA\Property(property="password", required=true, type="string", example="password")
 *           )
 *      ),
 * )
 */
Flight::route('POST /auth/login', function () {
    $data = json_decode(Flight::request()->getBody(),true);
    return Flight::authService()->login($data);
});
/**
 * @OA\Post(
 *      path="/auth/signup",
 *      tags={"auth"},
 *      summary="Register",
 *      @OA\Response(
 *           response=200,
 *           description="User data and JWT token"
 *      ),
 *      @OA\RequestBody(
 *          description="User Registeration",
 *          @OA\JsonContent(
 *             required={"email", "password", "username"},
 *             @OA\Property(property="email", required=true, type="string", example="test@test.test"),
 *             @OA\Property(property="password", required=true, type="string", example="password"),
 *             @OA\Property(property="username", required=true, type="string", example="username"),
 *           )
 *      ),
 * )
 */
Flight::route('POST /auth/signup', function () {
    $data = json_decode(Flight::request()->getBody(),true);
    return Flight::authService()->register($data);
});