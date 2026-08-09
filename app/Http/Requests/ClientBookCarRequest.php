<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientBookCarRequest extends FormRequest
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
            'pickupDate' => ['required', 'date'],
            'dropoffDate' => ['required', 'date', 'after:pickupDate'],

            // pickupLocation trebuie să existe în pivotul vehicle_location
            'pickupLocation' => [
                'required',
                'integer',
                'exists:vehicle_location,location_id'
            ],

            // rentalType trebuie să existe în pivotul rental_type_vehicle
            'rentalType' => [
                'required',
                'integer',
                'exists:rental_type_vehicle,rental_type_id'
            ],
        ];
    }
}
