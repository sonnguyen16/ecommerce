<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'birthday' => ['required', 'date'],
            'gender' => ['required'],
            'province' => ['required', 'integer'],
            'district' => ['required', 'integer'],
            'ward' => ['required', 'integer'],
            'address' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'max:2048'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'name.required' => 'Tên không được để trống',
            'name.string' => 'Tên phải là chuỗi',
            'name.max' => 'Tên không được quá 255 ký tự',
            'birthday.required' => 'Ngày sinh không được để trống',
            'birthday.date' => 'Ngày sinh phải là ngày',
            'gender.required' => 'Giới tính không được để trống',
            'province.required' => 'Tỉnh/Thành phố không được để trống',
            'province.integer' => 'Tỉnh/Thành phố phải là số nguyên',
            'district.required' => 'Quận/Huyện không được để trống',
            'district.integer' => 'Quận/Huyện phải là số nguyên',
            'ward.required' => 'Phường/Xã không được để trống',
            'ward.integer' => 'Phường/Xã phải là số nguyên',
            'address.required' => 'Địa chỉ không được để trống',
            'address.string' => 'Địa chỉ phải là chuỗi',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
            'avatar.max' => 'Hình ảnh không được vượt quá 2MB',
        ];
    }
}
