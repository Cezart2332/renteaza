<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'], // sau 'url'
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // galerie (path-uri S3)
            'images_new' => ['nullable', 'array'],
            'images_new.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'images_keep' => ['nullable', 'array'],
            'images_keep.*' => ['string', 'max:500', 'regex:/^[A-Za-z0-9_\-\/\.]+$/'],

            'images_order' => ['array'],  // opțional (doar existente)
            'images_order.*' => ['string'],

            // nou: ordinea completă (existente + placeholder-e de noi)
            'images_order_full' => ['array'],
            'images_order_full.*' => ['string'],

            'images_remove' => ['nullable', 'array'],
            'images_remove.*' => ['string', 'max:500', 'regex:/^[A-Za-z0-9_\-\/\.]+$/'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Denumirea firmei este obligatorie.',
            'email.email' => 'Adresa de email trebuie să fie validă.',
            'images_new.*.image' => 'Fiecare fișier din galerie trebuie să fie o imagine.',
        ];
    }

}
