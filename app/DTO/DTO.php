<?php

namespace App\DTO;

use App\Http\Requests\V1\BaseFormRequest;

abstract class DTO
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromRequest(BaseFormRequest $request): static
    {
        return new static(...$request->validated());
    }
}
