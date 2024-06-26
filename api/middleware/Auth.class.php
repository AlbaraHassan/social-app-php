<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    public function before($params)
    {
        $token = substr(Flight::request()->getHeader("Authorization"), 7);
        if (empty($token)) {
            return Flight::halt(
                401,
                json_encode(["message" => "Missing Token"])
            );
        }
        try {
            $payload = (array) JWT::decode(
                $token,
                new Key(ConfigService::getJwtSecret(), "HS256")
            );
        } catch (Exception $e) {
            return Flight::halt(
                401,
                json_encode(["message" => "Invalid Token"])
            );
        }
        $user = Flight::authService()->findByEmail($payload["email"]);
        if ($user) {
            unset($user["password"]);
            Flight::set("user", $user);
            User::setUser();
            return true;
        } else {
            return Flight::halt(
                401,
                json_encode(["message" => "Invalid Token"])
            );
        }
    }
}
