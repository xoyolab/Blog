<?php

namespace App\Http\Controllers\XControllers;

use App\XModels\Tag;
use App\XModels\Post;
use Canvas\Topic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * api get all posts data with json list.
     *
     * @return array
     *
     */
    public function list()
    {
        $posts = Post::where('complete', 1)
            ->orderByDesc('created_at')
            ->get();
        $data = [];
        foreach ($posts as $post) {
            $post['tags'] = $post->tags;
            $data[] = $post;
        }
        return ($data);
    }

    /**
     * show the given post data with slug.
     *
     * @param string $slug
     * @return array
     */
    public function show(Post $post)
    {
        $related_posts = Post::all()->except($post->id)->random(3);
        $relates = [];
        foreach ($related_posts as $index => $related_post) {
            $relates[$index]['post'] = $related_post;
            $relates[$index]['post']['tags'] = $related_post->tags;
        }
        $data = [
            'post'   => $post,
            'tags'   => $post->tags,
            'relates'=> $relates,

        ];
        return  $data;
    }

    public function findPostsByTagOrTopic($slug)
    {
        $word = Tag::where('slug', $slug)->first();
        if (is_null($word)) {
            $word = Topic::where('slug', $slug)->first();
        }
        if (isset($word)) {
            foreach ($word->posts as $post) {
                $post['tags'] = $post->tags;
                $data[] = $post;
            }
            return ($data);
        } else {
            return null;
        }
    }
}
