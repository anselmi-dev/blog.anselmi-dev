<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::withCount('posts')->has('posts')->orderBy('name')->get();

        return view('pages.tag.index')->with(compact('tags'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return view('pages.tag.show')->with(compact('tag'));
    }
}
