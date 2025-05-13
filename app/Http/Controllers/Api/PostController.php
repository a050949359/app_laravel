<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::get();

        if (isset($posts) && count($posts) > 0) {
            $data = ['posts' => $posts];
            return $this->makeJson(1,$data,null);
        } else {
            return $this->makeJson(0,null,'找不到任何文章');
        }
    }

    public function show(Request $request,$id)
    {
        $post = Post::find($id);

        if (isset($post)) {
            $data = ['post' => $post];
            return $this->makeJson(1,$data,null);
        } else {
            return $this->makeJson(0,null,'找不到該文章');
        }

    }

    public function store(Request $request)
    {
        $input = ['title' => $request->title , 'content' => $request->content];

        $post = Post::create($input);

        if (isset($post)) {
            $data = ['post' => $post];
            return $this->makeJson(1,$data,'新增文章成功');
        } else {
            $data = ['post' => $post];
            return $this->makeJson(0,null,'新增文章失敗');
        }

    }

    public function update(Request $request,$id)
    {

        try {
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();
        } catch (Throwable $e) {
            $data = ['post' => $post];
            return $this->makeJson(0,null,'更新文章失敗');
        }

        $data = ['post' => $post];
        return $this->makeJson(1,$data,'更新文章成功');
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
        } catch (Throwable $e) {
            return $this->makeJson(0,null,'刪除文章失敗');
        }
        return $this->makeJson(1,null,'刪除文章成功');
    }
}
