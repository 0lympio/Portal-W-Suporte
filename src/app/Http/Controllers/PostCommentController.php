<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $user = auth()->user()->id;
        $data = $request->all();
        $data['user_id'] = $user;

        if (auth()->user()->can('approvals.approver')) {
            $data['status_id'] = 1;
            $data['approved_by'] = $user;
            $data['approved_at'] = now();
        } else {
            $data['status_id'] = 0;
        }

        PostComment::create($data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function destroy(PostComment $postComment)
    {
        // $postComment = PostComment::find($request->id);
        $postComment->delete();
        return back();
    }
}
