<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => '',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'required_without:id|max:2048',
            'is_public' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài viết không được để trống',
            'title.string' => 'Tiêu đề bài viết phải là chuỗi',
            'title.max' => 'Tiêu đề bài viết không được vượt quá 255 ký tự',
            'content.required' => 'Nội dung bài viết không được để trống',
            'content.string' => 'Nội dung bài viết phải là chuỗi',
            'thumbnail.required_without' => 'Ảnh bài viết không được để trống',
            'thumbnail.max' => 'Ảnh bài viết không được vượt quá 2MB',
            'is_public.required' => 'Trạng thái công khai không được để trống',
            'is_public.boolean' => 'Trạng thái công khai không hợp lệ'
        ];
    }
}
