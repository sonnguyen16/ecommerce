<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::whereNull('deleted_at')->paginate(6);
        return response()->json($categories);
    }

    public function getCategory($category_id)
    {
        $category = Category::whereNull('deleted_at')->find($category_id);
        return response()->json($category);
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        if($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        $validated['slug'] = Str::slug($validated['name']);
        $category = Category::updateOrCreate(
            ['id' => $validated['id']],
            $validated
        );
        return response()->json($category);
    }

    public function deleteCategory($category_id): JsonResponse
    {
        $category = Category::find($category_id);

        if (!$category) {
            return response()->json(['message' => 'Không tìm thấy danh mục'], 404);
        }

        // Xóa danh mục sẽ soft delete
        $category->delete();

        return response()->json(['message' => 'Xóa danh mục thành công']);
    }
}
