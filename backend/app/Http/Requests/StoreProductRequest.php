<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'id' => '',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1000',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string',
            'sale_price' => 'required|numeric|lte:price|min:1000',
            'quantity' => 'required|numeric',
            'thumbnail' => 'required_without:id',
            'attributes' => 'required|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'seo_url' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.string' => 'Tên sản phẩm phải là chuỗi',
            'description.required' => 'Mô tả sản phẩm không được để trống',
            'description.string' => 'Mô tả sản phẩm phải là chuỗi',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 1000',
            'category_id.required' => 'Danh mục sản phẩm không được để trống',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại',
            'unit.required' => 'Đơn vị sản phẩm không được để trống',
            'unit.string' => 'Đơn vị sản phẩm phải là chuỗi',
            'sale_price.required' => 'Giá khuyến mãi không được để trống',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá sản phẩm',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 1000',
            'quantity.required' => 'Số lượng sản phẩm không được để trống',
            'quantity.numeric' => 'Số lượng sản phẩm phải là số',
            'thumbnail.required_without' => 'Ảnh sản phẩm không được để trống',
            'thumbnail.image' => 'Ảnh sản phẩm phải là ảnh',
            'attributes.required' => 'Thuộc tính sản phẩm không được để trống',
            'attributes.string' => 'Thuộc tính sản phẩm phải là chuỗi',
            'seo_title.string' => 'Tiêu đề SEO phải là chuỗi',
            'seo_description.string' => 'Mô tả SEO phải là chuỗi',
        ];
    }

}
