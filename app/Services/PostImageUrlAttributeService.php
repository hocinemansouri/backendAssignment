<?php
namespace App\Services;

class PostImageUrlAttributeService
{
    public function getImageUrlAttribute($post)
    {
        return asset('/upload/blog_images/' . $post->image);
    }
}