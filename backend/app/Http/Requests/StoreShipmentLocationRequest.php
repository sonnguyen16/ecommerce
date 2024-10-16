<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentLocationRequest extends FormRequest
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
            'order_detail_id' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
            'status' => ['required'],
            'address' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'order_detail_id.required' => 'Vui lòng nhập order_detail_id',
            'order_detail_id.integer' => 'order_detail_id phải là số',
            'status.required' => 'Vui lòng nhập trạng thái',
            'note.string' => 'Ghi chú phải là chuỗi',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'address.string' => 'Địa chỉ phải là chuỗi',
        ];
    }
}
