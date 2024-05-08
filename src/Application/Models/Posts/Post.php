<?php

namespace App\Application\Models\Posts;

use App\Application\Models\Users\User;

class Post {
    public int $id;
    public string $title;
    public string $content;
    public string $slug;
    public User $author;
}