<?php

namespace Mixdinternet\Articles\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Flash\Facades\Flash;
use Mixdinternet\Admix\Http\Controllers\AdmixController;
use Mixdinternet\Articles\Article;
use Mixdinternet\Articles\Http\Requests\CreateEditArticlesRequest;

class ArticlesAdminController extends AdmixController
{
    public function index(Request $request)
    {
        session()->put('backUrl', request()->fullUrl());

        $trash = ($request->segment(3) == 'trash') ? true : false;

        $query = Article::sort();
        ($trash) ? $query->onlyTrashed() : '';

        $search = [];
        $search['status'] = $request->input('status', '');
        $search['star'] = $request->input('star', '');
        $search['name'] = $request->input('name', '');

        ($search['status']) ? $query->where('status', $search['status']) : '';
        ($search['star'] != '') ? $query->where('star', $search['star']) : '';
        ($search['name']) ? $query->where('name', 'LIKE', '%' . $search['name'] . '%') : '';

        $articles = $query->paginate(50);

        $view['trash'] = $trash;
        $view['search'] = $search;
        $view['articles'] = $articles;

        return view('mixdinternet/articles::admin.index', $view);
    }

    public function create(Article $article)
    {
        $view['article'] = $article;

        return view('mixdinternet/articles::admin.form', $view);
    }

    public function store(CreateEditArticlesRequest $request)
    {
        if (Article::create($request->all())) {
            Flash::success('Item inserido com sucesso.');
        } else {
            Flash::error('Falha no cadastro.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.articles.index');
    }

    public function edit(Article $article)
    {
        $view['article'] = $article;

        return view('mixdinternet/articles::admin.form', $view);
    }

    public function update(Article $article, CreateEditArticlesRequest $request)
    {
        $input = $request->all();

        if (isset($input['remove'])) {
            foreach ($input['remove'] as $k => $v) {
                $article->{$v}->destroy();
                $article->{$v} = STAPLER_NULL;
            }
        }

        if ($article->update($input)) {
            Flash::success('Item atualizado com sucesso.');
        } else {
            Flash::error('Falha na atualização.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.articles.index');
    }

    public function destroy(Request $request)
    {
        if (Article::destroy($request->input('id'))) {
            Flash::success('Item removido com sucesso.');
        } else {
            Flash::error('Falha na remoção.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.articles.index');
    }

    public function restore($id)
    {
        $article = Article::onlyTrashed()->find($id);

        if (!$article) {
            abort(404);
        }

        if ($article->restore()) {
            Flash::success('Item restaurado com sucesso.');
        } else {
            Flash::error('Falha na restauração.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.articles.trash');
    }
}
