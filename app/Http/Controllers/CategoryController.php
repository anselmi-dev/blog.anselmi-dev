<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return view('pages.blog.index')->with(compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('pages.blog.category')->with(compact('category'));
    }
}
