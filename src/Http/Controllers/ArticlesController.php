<?php

namespace Mixdinternet\Articles\Http\Controllers;

use App\Http\Controllers\Controller;
use Mixdinternet\Articles\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    protected $fields = ['id', 'star', 'call', 'name', 'description', 'published_at', 'slug', 'image_file_name'];

    public function index(Request $request)
    {
        $limit = $request->get('limit', 5);

        return Article::active()->paginate($limit, $this->fields)
            ->addQuery('limit', $limit);
    }

    public function show($slug)
    {
        return Article::select($this->fields)->with(['seo', 'galleries.images'])->active()->where('slug', $slug)->first();
    }
}
