<?php

namespace App\Support;

require_once public_path('systemUpdater/SystemJson.php');

class SystemJson
{
    public static function path(): string
    {
        return \SystemUpdaterSystemJson::path();
    }

    public static function defaults(): array
    {
        return \SystemUpdaterSystemJson::defaults();
    }

    public static function read(bool $checkRemote = true): array
    {
        return \SystemUpdaterSystemJson::read($checkRemote);
    }

    public static function readObject(bool $checkRemote = true): object
    {
        return \SystemUpdaterSystemJson::readObject($checkRemote);
    }

    public static function write(array $payload): array
    {
        return \SystemUpdaterSystemJson::write($payload);
    }

    public static function checkNow(): array
    {
        return \SystemUpdaterSystemJson::checkNow();
    }

    public static function confirmUpdate(): array
    {
        return \SystemUpdaterSystemJson::confirmUpdate();
    }
}
