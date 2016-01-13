<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PostFormRequest;
use App\Post;
use Illuminate\Http\Request;
use Redirect;

class PostController extends Controller {

	/**
	 * Display a listing of posts.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$posts = Post::where( 'active', 1 )
		             ->orderBy( 'created_at', 'desc' )
		             ->paginate( config( 'blog.posts_per_page' ) );

		$title = config( 'blog.title' );

		return view( 'home' )
			->withPosts( $posts )
			->withTitle( $title );
	}

	/**
	 * Show the form for creating a new post.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create( Request $request ) {

		if ( $request->user()->can_post() ) {
			return view( 'post.create' );
		} else {
			return redirect( '/' )->withErrors( 'You have not sufficient permissions for writing post' );
		}
	}

	/**
	 * Store a newly created post in storage.
	 *
	 * @param PostFormRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( PostFormRequest $request ) {

		$post            = new Post();
		$post->title     = $request->get( 'title' );
		$post->content   = $request->get( 'content' );
		$post->author_id = $request->user()->id;

		if ( $this->getDuplicate($post->slug) ) {
			return redirect( 'new-post' )->withErrors( 'Title already exists.' )->withInput();
		} else if ( $request->has( 'save' ) ) {
			$post->active = 1;
			$message      = 'Post published successfully';
		} else {
			$post->active = 1;
			$message      = 'Post published successfully';
		}
		$post->save();

		return redirect( 'edit/' . $post->slug )->withMessage( $message );
	}

	/**
	 * Display the post
	 *
	 * @param $slug
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $slug ) {
		$post = Post::where( 'slug', $slug )->first();
		if ( ! $post ) {
			return redirect( '/' )->withErrors( 'requested page not found' );
		}

		return view( 'post.show' )->withPost( $post );
	}

	/**
	 * Show the form for editing the post.
	 *
	 * @param Request $request
	 * @param $slug
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( Request $request, $slug ) {
		$post = Post::where( 'slug', $slug )->first();
		if ( $post && ( $request->user()->id == $post->author_id || $request->user()->is_admin() ) ) {
			return view( 'post.edit' )->with( 'post', $post );
		}

		return redirect( '/' )->withErrors( 'you have not sufficient permissions' );
	}

	/**
	 * Update the specified post in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request ) {
		$post_id = $request->input( 'post_id' );
		$post    = Post::find( $post_id );

		if ( $post && ( $post->author_id == $request->user()->id || $request->user()->is_admin() ) ) {
			$post->title     = $request->input( 'title' );
			$duplicate = $this->getDuplicate($post->slug);

			if ( $duplicate ) {
				if ( $duplicate->id != $post_id ) {
					return redirect( 'edit/' . $post->slug )->withErrors( 'Title already exists.' )->withInput();
				}
			}

			$post->content  = $request->input( 'content' );

			if ( $request->has( 'save' ) ) {
				$post->active = 0;
				$message      = 'Post saved successfully';
				$landing      = 'edit/' . $post->slug;
			} else {
				$post->active = 1;
				$message      = 'Post updated successfully';
				$landing      = $post->slug;
			}
			$post->save();

			return redirect( $landing )->withMessage( $message );
		} else {
			return redirect( '/' )->withErrors( 'you have not sufficient permissions' );
		}
	}

	/**
	 * look for the duplicate of a slug, return a post object if exist
	 *
	 * @param $slug
	 *
	 * @return Post $post
	 */
	public function getDuplicate($slug){

		return Post::where( 'slug', $slug )->first();
	}

	/**
	 * Remove the specified post from storage.
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function destroy( Request $request, $id ) {
		$post = Post::find( $id );
		if ( $post && ( $post->author_id == $request->user()->id || $request->user()->is_admin() ) ) {
			$post->delete();
			$data['message'] = 'Post deleted Successfully';
		} else {
			$data['errors'] = 'Invalid Operation. You have not sufficient permissions';
		}

		return redirect( '/' )->with( $data );
	}

	/**
	 * Returns the user's instance of the author
	 *
	 */

	public function author() {
		return $this->belongsTo( 'App\User', 'author_id' );
	}
}
