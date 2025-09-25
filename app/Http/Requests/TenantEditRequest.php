<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantEditRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $this->route('id'),
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'flat_id' => 'nullable|exists:flats,id',
            'house_owner_id' => 'required|exists:users,id',
        ];
    }
}
