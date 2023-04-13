<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Collection;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

//    public function validated($key = null, $default = null): Collection
//    {
//        return collect(parent::validated($key, $default));
//    }
}
