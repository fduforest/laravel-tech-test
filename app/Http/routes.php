<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group( [ 'middleware' => 'web' ], function () {
	Route::auth();
	Route::get( '/', 'PostController@index' );

	// check for logged in user
	Route::group( [ 'middleware' => [ 'auth' ] ], function () {
		// users profile
		Route::get( 'user/{id}', 'ProfileController@show' )->where( 'id', '[0-9]+' );
		Route::post( 'user/{id}', 'ProfileController@updateProfile' )->where( 'id', '[0-9]+' );

		// show new post form
		Route::get( 'new-post', 'PostController@create' );
		// save new post
		Route::post( 'new-post', 'PostController@store' );
		// edit post form
		Route::get( 'edit/{slug}', 'PostController@edit' );
		// update post
		Route::post( 'update', 'PostController@update' );
		// delete post
		Route::get( 'delete/{id}', 'PostController@destroy' );
		// display user's all posts
		Route::get( 'user-posts', 'UserController@user_posts' );
		// display user's drafts
		Route::get( 'user-drafts', 'UserController@user_posts_draft' );
		// display snake game
		Route::get( 'snake', function () {
			return view( 'snake' );
		} );
		// update snake game score
		Route::post( 'snake', 'ProfileController@updatescore' );
	} );

	// display list of posts
	Route::get( 'user/{id}/posts', 'UserController@user_posts' )->where( 'id', '[0-9]+' );
	// display single post
	Route::get( '/{slug}', [ 'as' => 'post', 'uses' => 'PostController@show' ] )->where( 'slug', '[A-Za-z0-9-_]+' );

} );
