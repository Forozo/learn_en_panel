<?php

namespace App\Http\Utill;

class DefJson
{
    public static function success(): array {
        return ["success" => true];
    }

    public static function error(): array {
        return ["success" => false];
    }

}
