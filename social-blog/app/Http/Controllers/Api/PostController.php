<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\Post\IPostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $service;
    //
    public function __construct(IPostService $service)
    {
        $this->service = $service;
    }

    public function CreatePost(CreatePostRequest $request){
        return response()->json(
            $this->service->createPost($request->validated()),
        );
    }

    public function GetPost($id){
        return response()->json([
            $this->service->getPost($id),
        ]);
    }

    public function UpdatePost(UpdatePostRequest $request){
        return response()->json([
            $this->service->updatePost($request->validated()),
        ]);
    }
}
