<?php

namespace App\Http\Controllers;
use App\Models\Blog;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Cviebrock\EloquentSluggable\Services\SlugService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BlogController extends Controller
{
    // limit the views on which sould show when authenticated
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    // Show all Blog
    public function index(){
        return view('blog.index')
            ->with('blogs', Blog::orderBy('updated_at', 'DESC')->get());
    }

    // Create Blog View
    public function create(){
        return view('blog.create');
    }

    // Store Blog
    public function store(Request $request){

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048'
        ]);

        // Upload image to Cloudinary
        $uploadedFile = $request->file('image');
        $imageUrl = Cloudinary::upload($uploadedFile->getRealPath(), [
            'folder' => 'Blog Posts'
        ])->getSecurePath();

        // $newImageName = uniqid() . '-' . $request->title . '.' . $request->image->extension();

        // $request->image->move(public_path('blog_images'), $newImageName);
        $slug = SlugService::createSlug(Blog::class, 'slug', $request->title);

        Blog::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => $slug,
            'image_path' => $imageUrl,
            'user_id' => auth()->user()->id
        ]);

        return redirect(RouteServiceProvider::HOME)
            ->with('message', 'Your post has been added!');

    }

    // Show Specific blog post with slug
    public function show($slug){
        $blog = Blog::where('slug', $slug)
            ->with('user', 'comments.user:id,name', 'comments.replies.user')
            ->first();

        // dd($blog -> toArray());

        return view('blog.show', compact('blog'));
    }

    // Edit Blog View
    public function edit($slug)
    {
        return view('blog.edit')
            ->with('blog', Blog::where('slug', $slug)->first());
    }

    // Update Blog
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'mimes:jpg,png,jpeg|max:5048'
        ]);

        if ($request->image){
             // Upload image to Cloudinary
            $uploadedFile = $request->file('image');
            $imageUrl = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'Blog Posts'
            ])->getSecurePath();

            // $newImageName = uniqid() . '-' . $request->title . '.' . $request->image->extension();

            // $request->image->move(public_path('blog_images'), $newImageName);
        }else{
            // $newImageName = Blog::where('slug', $slug)->pluck('image_path')->first();
            $imageUrl = Blog::where('slug', $slug)->pluck('image_path')->first();
        }
        

        Blog::where('slug', $slug)
            ->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'slug' => SlugService::createSlug(Blog::class, 'slug', $request->title),
                'image_path' => $imageUrl,
                'user_id' => auth()->user()->id
            ]);

        return redirect(RouteServiceProvider::HOME)
            ->with('message', 'Your post has been updated Successfully!');
    }

    // Delete Blog
    public function delete($slug)
    {
        $blog = Blog::where('slug', $slug);
        $blog->delete();

        return redirect('/blog')
            ->with('message', 'Your post has been deleted Successfully!');
    }
}
