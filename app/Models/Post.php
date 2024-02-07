<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Services\HumanReadableCreatedAtService;
use App\Services\PostImageUrlAttributeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'category_id',
        'user_id',
    ];

    public $appends = [
        'image_url',
        'human_readable_created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }

    public function postlikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function getImageUrlAttribute()
    {
        $ImageUrlAttributeService = app(PostImageUrlAttributeService::class);

        return $ImageUrlAttributeService->getImageUrlAttribute($this);
    }

    public function getHumanReadableCreatedAtAttribute()
    {
        $humanReadableCreatedAtService = app(HumanReadableCreatedAtService::class);

        return $humanReadableCreatedAtService->getHumanReadableCreatedAtAttribute($this);
    }
}
