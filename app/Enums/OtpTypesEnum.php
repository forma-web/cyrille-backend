<?php

namespace App\Enums;

enum OtpTypesEnum: string
{
    use Arrayable;

    case REGISTER = 'register';
    case RESET_PASSWORD = 'reset_password';
}
