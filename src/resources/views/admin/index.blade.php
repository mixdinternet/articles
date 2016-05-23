@extends('admin.index')

@section('title')
    Listagem de {{ strtolower(config('marticles.name', 'Artigos')) }}
@endsection

@section('btn-insert')
    @if((!checkRule('admin.articles.create')) && (!$trash))
        @include('admin.partials.actions.btn.insert', ['route' => route('admin.articles.create'), 'title' => 'Marca'])
    @endif
    @if((!checkRule('admin.articles.trash')) && (!$trash))
        @include('admin.partials.actions.btn.trash', ['route' => route('admin.articles.trash')])
    @endif
    @if($trash)
        @include('admin.partials.actions.btn.list', ['route' => 'admin.articles.index'])
    @endif
@endsection

@section('btn-delete-all')
    @if((!checkRule('admin.articles.destroy')) && (!$trash))
        @include('admin.partials.actions.btn.delete-all', ['route' => 'admin.articles.destroy'])
    @endif
@endsection

@section('search')
    {!! Form::model($search, ['route' => ($trash) ? 'admin.articles.trash' : 'admin.articles.index', 'method' => 'get', 'id' => 'form-search'
        , 'class' => '']) !!}
    <div class="row">
        <div class="col-md-4">
            {!! BootForm::select('status', 'Status', ['' => '-', 'active' => 'Ativo', 'inactive' => 'Inativo'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::select('star', 'Destaque', ['' => '-', '1' => 'Sim', '0' => 'Não'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::text('name', 'Nome') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="{{ route(($trash) ? 'admin.articles.trash' : 'admin.articles.index') }}"
                   class="btn btn-default btn-flat">
                    <i class="fa fa-list"></i>
                    <i class="fs-normal hidden-xs">Mostrar tudo</i>
                </a>
                <button class="btn btn-success btn-flat">
                    <i class="fa fa-search"></i>
                    <i class="fs-normal hidden-xs">Buscar</i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('table')
    @if (count($articles) > 0)
        <table class="table table-striped table-hover table-action jq-table-rocket">
            <thead>
            <tr>
                @if((!checkRule('admin.articles.destroy')) && (!$trash))
                    <th>
                        <div class="checkbox checkbox-flat">
                            <input type="checkbox" id="checkbox-all">
                            <label for="checkbox-all">
                            </label>
                        </div>
                    </th>
                @endif
                <th>{!! columnSort('#', ['field' => 'id', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Nome', ['field' => 'name', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Destaque', ['field' => 'star', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Status', ['field' => 'status', 'sort' => 'asc']) !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($articles as $article)
                <tr>
                    @if((!checkRule('admin.articles.destroy')) && (!$trash))
                        <td>
                            @include('admin.partials.actions.checkbox', ['row' => $article])
                        </td>
                    @endif
                    <td>{{ $article->id }}</td>
                    <td>{{ $article->name }}</td>
                    <td>@include('admin.partials.label.yes-no', ['yesNo' => $article->star])</td>
                    <td>@include('admin.partials.label.status', ['status' => $article->status])</td>
                    <td>
                        @if((!checkRule('admin.articles.edit')) && (!$trash))
                            @include('admin.partials.actions.btn.edit', ['route' => route('admin.articles.edit', $article->id)])
                        @endif
                        @if((!checkRule('admin.articles.destroy')) && (!$trash))
                            @include('admin.partials.actions.btn.delete', ['route' => 'admin.articles.destroy', 'id' => $article->id])
                        @endif
                        @if($trash)
                            @include('admin.partials.actions.btn.restore', ['route' => 'admin.articles.restore', 'id' => $article->id])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        @include('admin.partials.nothing-found')
    @endif
@endsection

@section('pagination')
    {!! $articles->appends(request()->except(['page']))->render() !!}
@endsection

@section('pagination-showing')
    @include('admin.partials.pagination-showing', ['model' => $articles])
@endsection