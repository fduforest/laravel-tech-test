<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $guarded = [];

	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;
		$this->attributes['slug'] = str_slug($value);
	}

	public function author()
	{
		return $this->belongsTo('App\User','author_id');
	}
}