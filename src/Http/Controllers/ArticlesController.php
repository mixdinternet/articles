<?php

namespace Mixdinternet\Articles\Http\Controllers;

use App\Http\Controllers\FrontendController;
use Mixdinternet\Articles\Article;
use Illuminate\Http\Request;

class ArticlesController extends FrontendController
{
    protected $fields = ['id', 'star', 'name', 'description', 'published_at', 'slug', 'image_file_name'];

    public function index(Request $request)
    {
        $limit = $request->get('limit', 5);

        return Article::active()->paginate($limit, $this->fields)
            ->addQuery('limit', $limit);
    }

    public function show($slug)
    {
        return Article::select($this->fields)->with('galleries.images')->active()->where('slug', $slug)->first();
    }
}
