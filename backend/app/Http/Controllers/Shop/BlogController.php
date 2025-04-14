<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function getBlogs()
    {
        $blogs = Blog::whereNull('deleted_at')
            ->orderBy('created_at', 'desc');


        $total = Blog::whereNull('deleted_at')->count();

        return response()->json([
            'data' => $blogs->paginate(6),
            'total' => $total
        ]);
    }

    public function getBlog($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->whereNull('deleted_at')
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function storeBlog(StoreBlogRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($data['title']) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/blogs', $filename);
            $data['thumbnail'] = 'blogs/' . $filename;
        }

        if (isset($data['id'])) {
            $blog = Blog::find($data['id']);
            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bài viết không tồn tại'
                ], 404);
            }

            if ($request->hasFile('thumbnail') && $blog->thumbnail) {
                Storage::delete('public/' . $blog->thumbnail);
            }

            $blog->update($data);
        } else {
            $blog = Blog::create($data);
        }

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function deleteBlog($blog_id)
    {
        $blog = Blog::find($blog_id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại'
            ], 404);
        }

        if ($blog->thumbnail) {
            Storage::delete('public/' . $blog->thumbnail);
        }

        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bài viết thành công'
        ]);
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/uploads', $filename);

            $url = '/api/media/uploads/' . $filename;

            // CKEditor 4 response format
            $funcNum = $request->input('CKEditorFuncNum');
            if ($funcNum) {
                // Trả về định dạng cho CKEditor 4 callback
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
                return response($response)->header('Content-Type', 'text/html');
            }

            // Fallback cho CKEditor 5 hoặc các trường hợp khác
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'Không có file được tải lên'
            ]
        ], 400);
    }
}
