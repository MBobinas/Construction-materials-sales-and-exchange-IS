<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Listing;

class CommentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //get current user id
        $user_id = auth()->user()->id;
        $listings = Listing::where('user_id', $user_id)->get();
        $listing_ids = $listings->pluck('id');
        $comments = Comment::whereIn('listing_id', $listing_ids)
                    ->where('user_id', '!=', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('user.comments.index', compact('comments'));
    }

    public function sentComments()
    {
        $comments = Comment::where('user_id', auth()->user()->id)->get();

        return view('user.comments.sentComments', compact('comments'));
    }

    public function reply($id)
    {
        $listing = Listing::find($id);

        $comment = new Comment();
        $comment->author = request('author');
        $comment->listing_id = $listing->id;
        $comment->user_id = auth()->user()->id;
        $comment->text = request('comment');
        $comment->save();

        return redirect()->back()->with('success', 'Į komentarą atsakyta sėkmingai!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return redirect()->route('user.comments.sentComments')->with('success', 'Komentaras pašalintas sėkmingai');
    }
}
