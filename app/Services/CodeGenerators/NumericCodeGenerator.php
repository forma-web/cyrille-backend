<?php

namespace App\Services\CodeGenerators;

use App\Contracts\CodeGenerator;
use InvalidArgumentException;
use Random\Randomizer;

final readonly class NumericCodeGenerator implements CodeGenerator
{
    public function __construct(
        private Randomizer $randomizer,
    ) {
    }

    public function generate(int $length = 5): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Length must be greater than 0.');
        }

        if ($length > 10) {
            throw new InvalidArgumentException('Length must be less than 10.');
        }

        $min = 10 ** ($length - 1);
        $max = 10 ** $length - 1;

        return $this->randomizer->getInt($min, $max);
    }
}
