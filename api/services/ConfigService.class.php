<?php
\Dotenv\Dotenv::createImmutable('../')->load();

class ConfigService
{
    public static function getHost()
    {

        return $_ENV['HOST'];

    }

    public static function getUser()
    {
        return $_ENV['USER'];
    }

    public static function getPassword()
    {
        return $_ENV['PASSWORD'];
    }

    public static function getPort()
    {
        return $_ENV['PORT'];
    }

    public static function getDb()
    {
        return $_ENV['DATABASE'];
    }

    public static function getJwtSecret()
    {
        return $_ENV['JWT_SECRET'];
    }
}