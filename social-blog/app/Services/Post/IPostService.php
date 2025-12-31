<?php

namespace App\Services\Post;

use Symfony\Component\HttpFoundation\Request;

interface IPostService{
    public function createPost(array $data);
    public function getPost();
    public function deletePost();
    public function updatePost();
}