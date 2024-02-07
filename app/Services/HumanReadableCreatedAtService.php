<?php

namespace App\Services;

class HumanReadableCreatedAtService
{
    public function getHumanReadableCreatedAtAttribute($post)
    {
        return $post->created_at->diffForHumans();
    }
}
