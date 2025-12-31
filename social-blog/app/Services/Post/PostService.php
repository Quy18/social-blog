<?php

namespace App\Services\Post;

use Symfony\Component\HttpFoundation\Request;
use App\Models\Post;
use Exception;
use Illuminate\Support\Str;

class PostService implements IPostService{
    public function createPost(array $data)
    {
        $user = auth('api')->user();

        if(!$user){
            throw new Exception('Unauthenticated');
        }

        $slug = Str::slug($data['title']);
        $count = Post::where('slug', 'like', "$slug%")->count();

        if($count >= 0){
            $slug .= '-' . ($count + 1);
        }

        Post::create([
            'user_id' => auth('api')->user()->id,
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'published_at' => $data['published_at'],
            'featured_image' => $data['featured_image'],
        ]);

        return [
            'message' => 'Đăng bài viết thành công',
        ];
    }

    public function getPost()
    {
        $user = auth('api')->user();
        if(!$user){
            throw new Exception('Unauthenticated');
        }
        
    }

    public function deletePost()
    {
        throw new \Exception('Not implemented');
    }

    public function updatePost()
    {
        throw new \Exception('Not implemented');
    }
}