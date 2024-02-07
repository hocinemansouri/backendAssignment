<?php

namespace App\Repositories\Contracts;

use App\Models\PostLike;
use App\Services\AuthenticationService;

interface LikeRepositoryInterface
{
    public function getByPostIdAndUserId($postId,$userId): ?PostLike;
    public function toggleLike($postId,$userId, PostRepositoryInterface $postRepository,AuthenticationService $auth,$request);
}