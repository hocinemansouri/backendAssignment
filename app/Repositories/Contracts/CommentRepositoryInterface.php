<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function getAll();

    public function getById($commentId): ?Comment;

    public function getByPostId($postId, $with=null);

    public function getDeletedComments();

    public function create(array $data): Comment;

    public function update(Comment $commentId, array $data): Comment;

    public function delete(Comment $commentId): void;

    public function restoreDeletedComment($commentId): bool;
}
