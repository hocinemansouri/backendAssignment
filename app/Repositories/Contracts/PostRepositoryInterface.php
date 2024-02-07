<?php

namespace App\Repositories\Contracts;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAll($filters=null);

    public function getById($postId,$withCount=null,$with=null,$userId=null): ?Post;

    public function getDeletedPosts();

    public function create(array $data): ?Post;

    public function update($postId, array $data): ?Post;

    public function delete($postId);

    public function restoreDeletePost($postId): ?bool;

    
}