<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'posts', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'author_id' )->unsigned()->default( 0 );
			$table->foreign( 'author_id' )
			      ->references( 'id' )->on( 'users' )
			      ->onDelete( 'cascade' );
			$table->string( 'title' )->unique();
			$table->text('content');
			$table->string( 'slug' )->unique();
			$table->boolean( 'active' );
			$table->timestamps();
			$table->timestamp('published_at')->index();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'posts' );
	}
}
