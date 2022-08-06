<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Exceptions\MaximumDepthException as MaxException;

final class PostController extends Controller
{
    /**
     * Get a single post including comments ordered by the latest
     *
     * @return App\Models\Post
     */
    public function index(): Post
    {
        $post = Post::withCount('comments')->firstOrFail();
        $post->comments = Comment::where('post_id', $post->id)
            ->withDepth()
            ->orderBy('created_at')
            ->get()
            ->toTree();
        return $post;
    }

    /**
     * Validate and insert a single comment
     *
     * @return bool
     */
    public function CreateComment(CreateCommentRequest $request): Comment
    {
        if ($request->filled('parent_id')) {
            $parentComment = Comment::where('id', $request->parent_id)
                                            ->withDepth()
                                            ->orderBy('created_at')
                                            ->first();
            if ($parentComment->depth >= config('app.maxdepth')) {
                throw new MaxException();

            }
        }

        return Comment::create($request->validated());
    }

       public function DeleteComment($id)
    {
        $WillbeDeleted = Comment::where('id',$id)
                        ->withDepth()
                        ->orderBy('created_at')
                        ->first();
        if($WillbeDeleted){
            $WillbeDeleted->delete();
        }

        return response()->json([
            "success" => true,
            "message" => "Product deleted successfully.",
            "data" => $WillbeDeleted
            ]);

    }

    public function UpdateComment(UpdateCommentRequest $request)
    {
        if($request->validated()){
            $WillbeUpdated = Comment::where('id',$request->id)
            ->withDepth()
            ->orderBy('created_at')
            ->first();

            $WillbeUpdated->update($request->only('author','content'));

            return response()->json([
            "success" => true,
            "message" => "Product deleted successfully.",
            "data" => $WillbeUpdated
            ]);
        }

    }

}
