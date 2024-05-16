<?php

class User {
    private static array $user;

    public static function setUser(): void {
        self::$user = Flight::get('user');
    }

    public static function id(): int {
        return self::$user['id'];
    }
}
