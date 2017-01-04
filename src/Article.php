<?php

namespace Mixdinternet\Articles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Venturecraft\Revisionable\RevisionableTrait;
use Carbon\Carbon;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Mixdinternet\Seo\SeoTrait;
use Mixdinternet\Seo\SeoInterface;
use Mixdinternet\Galleries\GalleriableTrait;
use Mixdinternet\Galleries\GalleriableInterface;

class Article extends Model implements SluggableInterface, SeoInterface, StaplerableInterface, GalleriableInterface
{
    use SoftDeletes, SluggableTrait, SeoTrait, RevisionableTrait, EloquentTrait, GalleriableTrait;

    protected $revisionCreationsEnabled = true;

    protected $revisionFormattedFieldNames = [
        'name' => 'nome'
        , 'call' => 'chamada'
        , 'star' => 'destaque'
        , 'description' => 'descrição'
        , 'published_at' => 'data de publicação'
    ];

    protected $revisionFormattedFields = [
        'star' => 'boolean:Não|Sim',
    ];

    protected $seomap = [ /* mover para config */
        'title' => ['name'],
        'description' => ['description']
    ];

    protected $dates = ['deleted_at', 'published_at'];

    protected $fillable = ['status', 'star', 'call', 'name', 'description', 'image', 'published_at'];

    public function __construct(array $attributes = [])
    {
        $this->hasAttachedFile('image', [
            'styles' => [
                'crop' => function ($file, $imagine) {
                    $image = $imagine->open($file->getRealPath());
                    if (request()->input('crop.image.w', 0) > 0 && request()->input('crop.image.y', 0) > 0) {
                        $image->crop(new \Imagine\Image\Point(request()->input('crop.image.x'), request()->input('crop.image.y'))
                            , new \Imagine\Image\Box(request()->input('crop.image.w'), request()->input('crop.image.h')));
                    }
                    return $image;
                }
            ],
            /*'default_url' => '/assets/img/avatar.png',*/
        ]);

        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();

        static::bootStapler();
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'image' => $this->attachedFiles['image']->url()
            , 'image_crop' => $this->attachedFiles['image']->url('crop')
        ]);
    }

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = Carbon::createFromFormat('d/m/Y H:i', $value)
            ->toDateTimeString();
    }

    public function scopeSort($query, $fields = [])
    {
        if (count($fields) <= 0) {
            $fields = [
                'status' => 'asc'
                , 'star' => 'desc'
                , 'published_at' => 'desc'
                , 'name' => 'asc'
            ];
        }

        if (request()->has('field') && request()->has('sort')) {
            $fields = [request()->get('field') => request()->get('sort')];
        }

        foreach ($fields as $field => $order) {
            $query->orderBy($field, $order);
        }
    }

    public function scopeActive($query)
    {
        $query->where('status', 'active')->where('published_at', '<=', Carbon::now())->sort();
    }

    # revision
    public function identifiableName()
    {
        return $this->name;
    }
}