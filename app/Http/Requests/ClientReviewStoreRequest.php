<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientReviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'rating'      => [
                'required',
                'numeric',
                'between:0.5,5',
                'regex:/^(?:0\.5|[1-4](?:\.5)?|5(?:\.0)?)$/',
            ],
            'title'      => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ];
    }
}
