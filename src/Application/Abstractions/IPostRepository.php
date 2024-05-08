<?php

namespace App\Application\Abstractions;


use App\Application\Models\Posts\Post;

interface IPostRepository {
    /**
     * @return Post[]
     */
    public function getAllPosts() : array;

    public function getPostBySlug(string $slug) : Post;
}