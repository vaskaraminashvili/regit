<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:60',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->input('plan') === 'installment' && (int) $value < 5) {
                        $fail('განვადების მინიმალური ვადა 5 თვეა.');
                    }
                },
            ],
            'discount_code' => ['nullable', 'string', 'max:100'],
            'plan' => ['nullable', 'in:bnpl,installment'],
        ];
    }
}
