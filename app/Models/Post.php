<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'user_id',
    ];

    public $appends = [
        'image_url',
        'human_readable_created_at'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(PostLike::class);
    }
    
    public function getImageUrlAttribute(){
        return asset('/upload/blog_images/'.$this->image);
    }
    public function getHumanReadableCreatedAtAttribute(){
        return $this->created_at->diffForHumans();
    }
}
