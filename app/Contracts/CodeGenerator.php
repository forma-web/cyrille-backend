<?php

namespace App\Contracts;

interface CodeGenerator
{
    public function generate(?int $length): string;
}
