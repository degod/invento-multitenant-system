<?php

namespace App\Http\Requests;

use App\Enums\BillStatuses;
use Illuminate\Foundation\Http\FormRequest;

class BillEditRequest extends FormRequest
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
            'month' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:' . BillStatuses::UNPAID . ',' . BillStatuses::PAID],
            'bill_category_id' => ['required', 'exists:bill_categories,id'],
            'flat_id' => ['required', 'exists:flats,id'],
            'house_owner_id' => ['required', 'exists:users,id'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}