<?php
namespace App\Services;

class ProfileImageService
{
    public function getProfileImageUrl($user)
    {
        if ($user->profile_photo) {
            return asset('/upload/profile_images/' . $user->profile_photo);
        } else {
            return 'https://ui-avatars.com/api/?size=0.7&name='.urlencode($user->name."+".$user->surname);
        }
    }
}