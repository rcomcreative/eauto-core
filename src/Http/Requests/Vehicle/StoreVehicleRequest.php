<?php

namespace Eauto\Core\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Keep Requests reusable: do authorization via policies in each app.
        return true;
    }

    public function rules(): array
    {
        return [
            'make_id' => ['required', 'string', 'max:36'], // or 'uuid' if true UUID
            'Active_flag' => ['sometimes', 'boolean'],
        ];
    }
}