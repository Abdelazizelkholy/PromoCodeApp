<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCode extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'expiry_date' => 'required|date',
            'max_usages' => 'required|integer|min:1',
            'max_usages_per_user' => 'required|integer|min:1',
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,id',
            'type' => 'required|in:percentage,value',
            'amount' => 'required|numeric'
        ];
    }
}
