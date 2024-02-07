<?php

namespace App\Models;

use App\Services\HumanReadableCreatedAtService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'content',
        'post_id',
        'user_id'
    ];

    public $appends = [
        'human_readable_created_at'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getHumanReadableCreatedAtAttribute()
    {
        $humanReadableCreatedAtService = app(HumanReadableCreatedAtService::class);

        return $humanReadableCreatedAtService->getHumanReadableCreatedAtAttribute($this);
    }
}
