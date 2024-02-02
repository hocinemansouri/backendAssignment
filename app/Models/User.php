<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'username',
        'nickname',
        'email',
        'password',
        'profile_photo',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',

    ];
    public $appends = [
        'profile_image_url',
    ];
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('/upload/profile_images/' . $this->profile_photo);
        } else {
            return 'https://ui-avatars.com/api/?background=random&name=' . urlencode($this->name);
        }
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
