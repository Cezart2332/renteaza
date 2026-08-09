<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCheckInPhotosRequest extends FormRequest
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
            'photos'   => ['required', 'array', 'size:4'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,heic,heif', 'max:8192'], // ~8MB/poză
        ];
    }

    public function messages(): array
    {
        return [
            'photos.size' => 'Trebuie să încarci exact 4 fotografii pentru check-in.',
        ];
    }
}
