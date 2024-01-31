<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'user_id',
        'category_id'
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
