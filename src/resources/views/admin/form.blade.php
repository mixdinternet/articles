@extends('admin.form')

@section('title')
    Gerenciar {{ strtolower(config('marticles.name', 'Artigos')) }}
@endsection

@section('form')
    {!! BootForm::horizontal(['model' => $article, 'store' => 'admin.articles.store', 'update' => 'admin.articles.update'
        , 'id' => 'form-model', 'class' => 'form-horizontal form-rocket jq-form-validate jq-form-save'
        , 'files' => true ]) !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Geral</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">SEO</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                @if ($article['id'])
                    {!! BootForm::text('id', 'Código', null, ['disabled' => true]) !!}
                @endif

                {!! BootForm::select('status', 'Status', ['active' => 'Ativo', 'inactive' => 'Inativo'], null
                    , ['class' => 'jq-select2', 'data-rule-required' => true]) !!}

                {!! BootForm::select('star', 'Destaque', ['0' => 'Não', '1' => 'Sim'], null
                    , ['class' => 'jq-select2', 'data-rule-required' => true]) !!}

                {!! BootForm::text('name', 'Nome', null, ['data-rule-required' => true, 'maxlength' => '150']) !!}

                {!! BootForm::textarea('description', 'Descrição', null, ['data-rule-required' => true]) !!}

                @if (config('marticles.image', true))
                {!! BootForm::file('image', 'Imagem', [
                        'data-allowed-file-extensions' => '["jpg", "png"]',
                        'data-initial-preview' => '["<img src=\"' . $article->image->url('crop') . '\" class=\"file-preview-image\">"]',
                        'data-initial-caption' => $article->image->originalFilename(),
                        'data-min-image-width' => config('marticles.image.width', 640),
                        'data-min-image-height' => config('marticles.image.height', 480),
                        'data-aspect-ratio' => number_format(config('marticles.image.width', 640)/config('marticles.image.height', 480), 2)
                    ]) !!}
                @endif

                {!! BootForm::text('published_at', 'Data de publicação', ($article->published_at) ? $article->published_at->format('d/m/Y H:i') : null
                    , ['data-rule-required' => true, 'class' => 'jq-datetimepicker', 'maxlength' => '16']) !!}

            </div>
            <div class="tab-pane" id="tab_2">
                @include('mixdinternet/seo::partials.form', ['model' => isset($article) ? $article : null])
            </div>
        </div>
    </div>
    {!! BootForm::close() !!}
@endsection