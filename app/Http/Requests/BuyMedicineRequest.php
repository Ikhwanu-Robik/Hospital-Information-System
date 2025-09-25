<?php

namespace App\Http\Requests;

use App\Rules\PrescriptionIsNotPaid;
use Illuminate\Foundation\Http\FormRequest;

class BuyMedicineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required',
            'doctor_profile_id' => 'required',
            'id' => [
                'exists:prescription_records,id',
                new PrescriptionIsNotPaid
            ]
        ];
    }
}
