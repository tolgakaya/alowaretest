<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Comment;

class CommentTest extends TestCase
{
      /**
     * create comment test.
     *
     * @return void
     */
    public function test_create_comment()
    {
        $post = Post::create(['title' => 'test', 'content' => 'test']);
        $data = ['post_id' => $post->id, 'author' => 'Aloware', 'content' => 'test comment'];
        $response = $this->postJson('/api/comment', $data);
        $response
            ->assertStatus(201)
            ->assertJson(
                [
                    'post_id' => $post->id,
                    'author' => 'Aloware',
                    'content' => 'test comment',
                    'parent_id' => null,
                ]
            );
    }

       /**
     * update comment test.
     *
     * @return void
     */
    public function test_update_comment()
    {
        $post = Post::create(['title' => 'test', 'content' => 'test']);
        $comment = Comment::create(['post_id' => $post->id, 'author' => 'test author','content'=>'test content','parent_id'=>null]);

        $data = ['id' => $comment->id, 'author' => 'Aloware test', 'content' => 'test comment last'];
        $response = $this->putJson('/api/comment', $data);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data'=> [
                    'id'=>$comment->id,
                    'post_id' => $post->id,
                    'author' => 'Aloware test',
                    'content' => 'test comment last',
                    'parent_id' => $comment->parent_id,
                ]

            ]
            );
    }

               /**
     * delete comment test.
     *
     * @return void
     */
    public function test_delete_comment()
    {
        $post = Post::create(['title' => 'test', 'content' => 'test']);
        $comment = Comment::create(['post_id' => $post->id, 'author' => 'test author','content'=>'test content','parent_id'=>null]);

        $response = $this->deleteJson('/api/comment', ['id'=>$comment->id]);
        $response->assertStatus(405);

    }


}
