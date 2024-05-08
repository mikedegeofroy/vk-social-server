<?php

namespace App\Application\Contracts;

use App\Application\Models\Posts\PostDto;

interface IPostService {
    /**
     * @return PostDto[]
     */
    public function getPosts() : array;

    public function getPostBySlug(string $slug) : PostDto;
}