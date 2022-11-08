<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\PostComment;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Foundation\Application;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:approvals.index')->only('index');
        $this->middleware('permission:approvals.approver')->only('approver');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */

    public function index()
    {
        $approvals = DB::table('post_comments')
            ->select('post_comments.id', 'users.name', 'users.last_name', 'post_comments.comment', 'posts.title', 'post_comments.created_at', 'post_comments.status_id')
            ->leftJoin('users', 'users.id', '=', 'post_comments.user_id')
            ->leftJoin('posts', 'posts.id', '=', 'post_comments.post_id')
            ->where('post_comments.status_id', '=', '0')
            ->where('post_comments.deleted_at', null)
            ->get();

        return view('approvals.index', compact('approvals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approver(Request $request)
    {
        $post = PostComment::find($request->id);
        $user = auth()->user()->id;

        $post->fill([
            'status_id' => $request->status,
            'approved_by' => $user,
            'approved_at' => now()
        ]);
        $post->save();

        return redirect()->route('approvals.index')->with('message', 'Pendência atualizada com sucesso!');
    }
}
