<?php

namespace App\Http\Requests;

use App\Enums\CarType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerCarStoreRequest extends FormRequest
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
        $current = (int) date('Y');

        return [
            'vehicle_type_id' => ['required', 'integer', 'exists:vehicle_types,id'],
            'car_type' => ['required', Rule::enum(CarType::class)],
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . ($current + 5)],
            'description' => ['nullable', 'string', 'max:5000'],

            // Specificații
            'fuel_type_id' => ['required', 'integer', 'exists:fuel_types,id'],
            'transmission_id' => ['required', 'integer', 'exists:transmissions,id'],
            'seats' => ['required', 'integer', 'min:1', 'max:9'],
            'doors' => ['required', 'integer', 'min:2', 'max:6'],
            'autonomy_km' => ['nullable', 'integer', 'min:0'],
            'battery_capacity_kwh' => ['nullable', 'numeric', 'min:0'],
            'max_speed_kph' => ['nullable', 'integer', 'min:0'],
            'cargo_volume_liters' => ['nullable', 'integer', 'min:0'],

            // Plăcuță & preț
            'license_plate' => ['required', 'string', 'max:32', 'regex:/^[A-Za-z0-9\s\-]+$/'],
            'price_per_day' => ['required', 'numeric', 'min:0'],

            // Relații
            'rental_type_ids' => ['required', 'array'],
            'rental_type_ids.*' => ['integer', 'distinct', 'exists:rental_types,id'],

            // Locații ca obiecte
            'locations' => ['required', 'array', 'min:1'],
            'locations.*.name' => ['required', 'string', 'max:255'],
            'locations.*.address' => ['required', 'string', 'max:255'],
            'locations.*.city' => ['required', 'string', 'max:255'],
            'locations.*.postal_code' => ['required', 'string', 'max:50'],
            'locations.*.country' => ['required', 'string', 'max:120'],
            'locations.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'locations.*.longitude' => ['required', 'numeric', 'between:-180,180'],

            // Fișiere
            'cover_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery_images' => ['required', 'array', 'max:5'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ];
    }
    public function messages(): array
    {
        return [
            'license_plate.regex' => 'Numărul de înmatriculare poate conține doar litere, cifre, spațiu și „-”.',
        ];
    }
}
