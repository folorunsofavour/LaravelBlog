<?php

namespace App\Http\Controllers;
use App\Models\Blog;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('welcome')
            ->with('blog', Blog::orderBy('updated_at', 'DESC')->first());
    }
}
