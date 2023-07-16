<?php

namespace App\DTO\V1;

use App\Http\Requests\V1\FormRequest;

abstract readonly class DTO
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromRequest(FormRequest $request): static
    {
        // @phpstan-ignore-next-line
        return new static(...$request->validated());
    }
}
