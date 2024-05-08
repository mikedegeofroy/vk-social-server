<?php

namespace App\Application\Models\Posts;

use App\Application\Models\Users\UserDto;

class PostDto {
    public string $title;
    public string $content;
    public string $slug;
    public UserDto $author;
}