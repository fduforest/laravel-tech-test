<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {
	/*
	 * Display active posts of a particular user
	 *
	 * @param int $id
	 * @return view
	 */
	public function user_posts( $id ) {

		$posts = Post::where( 'author_id', $id )
		             ->where( 'active', 1 )
		             ->orderBy( 'created_at', 'desc' )
		             ->paginate( config('blog.posts_per_page') );

		$title = User::find( $id )->name;

		return view( 'home' )
			->withPosts( $posts )
			->withTitle( $title );
	}

	/*
	 * Display all of the posts of a particular user
	 *
	 * @param Request $request
	 * @return view
	 */
	public function user_posts_all( Request $request ) {

		$user  = $request->user();
		$posts = Post::where( 'author_id', $user->id )->orderBy( 'created_at', 'desc' )->paginate( 5 );
		$title = $user->name;

		return view( 'home' )
			->withPosts( $posts )
			->withTitle( $title );
	}

	/*
	 * Display draft posts of a currently active user
	 *
	 * @param Request $request
	 * @return view
	 */
	public function user_posts_draft( Request $request ) {

		$user  = $request->user();
		$posts = Post::where( 'author_id', $user->id )->where( 'active', 0 )->orderBy( 'created_at', 'desc' )->paginate( 5 );
		$title = $user->name;

		return view( 'home' )
			->withPosts( $posts )
			->withTitle( $title );
	}

}