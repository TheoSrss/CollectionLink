<?php

namespace App\Security;

class CodeService
{

    public static function generateNewCode()
    {
        return random_int(100000, 999999);
    }
}
