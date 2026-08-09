<?php

namespace App\Http\Requests;

use App\Enums\CarType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerCarUpdateRequest extends FormRequest
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
            'vehicle_type_id' => ['nullable', 'integer', 'exists:vehicle_types,id'],
            'car_type' => ['nullable', Rule::enum(CarType::class)],
            'brand' => ['nullable', 'string', 'max:120'],
            'model' => ['nullable', 'string', 'max:120'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . ($current + 5)],
            'description' => ['nullable', 'string', 'max:5000'],

            // Specificații
            'fuel_type_id' => ['nullable', 'integer', 'exists:fuel_types,id'],
            'transmission_id' => ['nullable', 'integer', 'exists:transmissions,id'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:9'],
            'doors' => ['nullable', 'integer', 'min:2', 'max:6'],
            'autonomy_km' => ['nullable', 'integer', 'min:0'],
            'battery_capacity_kwh' => ['nullable', 'numeric', 'min:0'],
            'max_speed_kph' => ['nullable', 'integer', 'min:0'],
            'cargo_volume_liters' => ['nullable', 'integer', 'min:0'],

            // Plăcuță & preț
            'license_plate' => ['nullable', 'string', 'max:32', 'regex:/^[A-Za-z0-9\s\-]+$/'],
            'price_per_day' => ['nullable', 'numeric', 'min:0'],

            // Relații
            'rental_type_ids' => ['nullable', 'array'],
            'rental_type_ids.*' => ['integer', 'distinct', 'exists:rental_types,id'],

            // Locații ca obiecte
            'locations' => ['nullable', 'array', 'min:1'],
            'locations.*.id' => ['nullable', 'integer', 'exists:locations,id'],
            'locations.*.name' => ['nullable', 'string', 'max:255'],
            'locations.*.address' => ['nullable', 'string', 'max:255'],
            'locations.*.city' => ['nullable', 'string', 'max:255'],
            'locations.*.postal_code' => ['nullable', 'string', 'max:50'],
            'locations.*.country' => ['nullable', 'string', 'max:120'],
            'locations.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'locations.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // Fișiere
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'new_images_to_save' => ['nullable', 'array'],
            'new_images_to_save.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'images_to_remove' => ['nullable', 'array'],
        ];
    }
    public function messages(): array
    {
        return [
            'license_plate.regex' => 'Numărul de înmatriculare poate conține doar litere, cifre, spațiu și „-”.',
        ];
    }
}