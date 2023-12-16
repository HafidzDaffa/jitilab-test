<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BlogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::select('blogs.*')->get();

        return response()->json([
            'message' => 'Blog list',
            'data' => $blogs
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,png,webp|max:1028',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error validation create blog',
                'errors' => $validator->errors(), 
            ], 400);
        }

        try {

            if($request->image) {
                $file = $request->image;
                $file_name = 'image' . time() . '_' . rand(1000, 9999) . '.' . $file->extension();
                $path = 'blog_image/'.$file_name;
                Storage::disk('public')->put($path, $file->get());
            }
    
            $blog = Blog::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $path,
            ]);
    
            return response()->json([
                'message' => 'Blog created successfully',
                'data' => $blog,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Blog, failed to create',
                // 'errors' => $th->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);
        
        return response()->json([
            'message' => 'Show Blog',
            'data' => $blog
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,webp|max:1028',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error validation update blog',
                'errors' => $validator->errors(), 
            ], 400);
        }

        try {

            $blog = Blog::find($id);

            if ($request->hasFile('image')) {
                $file = $request->image;
                Storage::disk('public')->delete($blog->image);
            
                $file_name = 'image' . time() . '_' . rand(1000, 9999) . '.' . $file->extension();
                $path = 'blog_image/' . $file_name;
                Storage::disk('public')->put($path, $file->get());
            
                $updateData = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $path,
                ];
            } else {
                $updateData = [
                    'title' => $request->title,
                    'description' => $request->description,
                ];
            }
            
            $blog = $blog->update($updateData);

            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => $blog
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Blog, failed to create',
                'errors' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);

        if(empty($blog)) {
            return response()->json([
                'message' => 'Blog not found'
            ], 404);
        }

        if($blog->image){
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully'
        ]);
    }
}
