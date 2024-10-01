<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::paginate(6);
        return response()->json($categories);
    }

    public function getCategory($category_id)
    {
        $category = Category::find($category_id);
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
}
